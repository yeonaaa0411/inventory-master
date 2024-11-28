from flask import Flask, jsonify
import pandas as pd
import mysql.connector
from statsmodels.tsa.arima.model import ARIMA
from sklearn.metrics import mean_absolute_percentage_error
import numpy as np

app = Flask(__name__)

# Function to convert numpy types to native Python types
def convert_to_native_types(obj):
    if isinstance(obj, (np.int64, np.float64)):
        return obj.item()  # Converts to native int or float
    elif isinstance(obj, dict):
        return {k: convert_to_native_types(v) for k, v in obj.items()}
    elif isinstance(obj, list):
        return [convert_to_native_types(v) for v in obj]
    return obj  # Return other types as is

@app.route('/predict_sales', methods=['GET'])
def predict_sales():
    # Database configuration
    db_config = {
        'host': 'localhost',
        'user': 'root',
        'password': '',
        'database': 'bpsr_inv'
    }

    connection = mysql.connector.connect(**db_config)
    cursor = connection.cursor()

    # Fetch sales data
    query = """
    SELECT sales.date, qty, sales.product_id, price, products.name AS product_name 
    FROM sales 
    JOIN products ON sales.product_id = products.id 
    ORDER BY sales.date
    """
    cursor.execute(query)
    data = cursor.fetchall()

    # Prepare data into a pandas DataFrame
    df = pd.DataFrame(data, columns=['date', 'qty', 'product_id', 'price', 'product_name'])
    df['date'] = pd.to_datetime(df['date'])
    df.set_index('date', inplace=True)

    # Aggregate sales by quarter
    df['total_price'] = df['qty'] * df['price']
    df_quarterly = df.resample('Q').sum()

    # Additional step to calculate the count of sales per quarter
    sales_count_quarterly = df.resample('Q').size()  # Count the number of sales orders per quarter

    # Use past data from January 2022 to the current day for training
    training_data = df_quarterly['qty']['2022-01-01':pd.Timestamp.today()]  # Data from Jan 2022 to the current day
    sales_count_training_data = sales_count_quarterly['2022-01-01':pd.Timestamp.today()]  # Sales count training data

    # Fit ARIMA model to the historical data for quantity prediction
    model = ARIMA(training_data, order=(1, 1, 1))  # Adjust (p, d, q) as needed
    model_fit = model.fit()

    # Fit ARIMA model to the historical data for sales count prediction
    sales_count_model = ARIMA(sales_count_training_data, order=(1, 1, 1))  # Adjust (p, d, q) for sales count
    sales_count_model_fit = sales_count_model.fit()

    # Predict future sales quantities for the next 5 quarters (starting from Q4 2024)
    forecast_steps = 5  # Forecasting from Q4 2024 and for the next 5 quarters
    forecast_qty = model_fit.forecast(steps=forecast_steps)

    # Predict future sales count for the next 5 quarters
    forecast_sales_count = sales_count_model_fit.forecast(steps=forecast_steps)

    # Prepare predicted data for the next 5 quarters
    future_quarters = pd.date_range(start='2024-12-31', periods=forecast_steps, freq='Q')
    predictions = pd.DataFrame({'date': future_quarters, 'predicted_qty': forecast_qty, 'predicted_sales_count': forecast_sales_count})

    # Predict specific product quantities for future quarters
    product_predictions = []
    product_revenue_predictions = []
    for product_id, product_name in df[['product_id', 'product_name']].drop_duplicates().values:
        product_data = df[df['product_id'] == product_id].resample('Q').sum()
        product_training_data = product_data['qty']['2022-01-01':pd.Timestamp.today()]  # Using up to current date

        product_model = ARIMA(product_training_data, order=(1, 1, 1))
        product_model_fit = product_model.fit()
        product_forecast = product_model_fit.forecast(steps=forecast_steps)

        # Append the predicted quantity for each product
        product_predictions.append({
            'product_name': product_name,
            'predicted_qty': product_forecast
        })
        
        # Revenue predictions for the products
        revenue_forecast = []
        for i in range(forecast_steps):
            price = df[df['product_id'] == product_id]['price'].iloc[0]
            
            # Convert price to float to avoid multiplication with Decimal
            price = float(price)
            
            revenue_forecast.append(product_forecast[i] * price)

        product_revenue_predictions.append({
            'product_name': product_name,
            'predicted_revenue': revenue_forecast
        })

    # Calculate the accuracy of the model using historical data (e.g., past 4 quarters)
    historical_train = df_quarterly['qty'][:-4]  # Exclude last 4 quarters for testing
    historical_test = df_quarterly['qty'][-4:]
    historical_model = ARIMA(historical_train, order=(1, 1, 1))
    historical_fit = historical_model.fit()
    historical_forecast = historical_fit.forecast(steps=4)
    mape = mean_absolute_percentage_error(historical_test, historical_forecast)

    # Prepare final response with all predictions
    result = {
        'accuracy': f"{(1 - mape) * 100:.2f}%",
        'predictions': []
    }

    # Add the predicted sales quantities, sales count, and revenues for future quarters
    for i, date in enumerate(future_quarters):
        total_revenue = 0
        for product_pred in product_predictions:
            product_qty = product_pred['predicted_qty'][i]
            price = df[df['product_name'] == product_pred['product_name']]['price'].iloc[0]
            
            # Convert price to float to avoid multiplication with Decimal
            price = float(price)
            
            total_revenue += product_qty * price

        # Correct the quarter assignment logic to reflect correct quarter names
        quarter_number = ((date.month - 1) // 3) + 1  # This ensures the quarter is correctly mapped
        result['predictions'].append({
            'date': date.strftime('%a, %d %b %Y %H:%M:%S GMT'),
            'predicted_qty': predictions['predicted_qty'].iloc[i],
            'predicted_sales_count': predictions['predicted_sales_count'].iloc[i],
            'predicted_revenue': total_revenue,
            'quarter': quarter_number,  # Correct quarter calculation
            'year': date.year
        })

    # Calculate predicted sales for December (1 month prediction)
    december_forecast_qty = model_fit.forecast(steps=1)[0]  # 1 month prediction
    december_forecast_sales_count = sales_count_model_fit.forecast(steps=1)[0]  # 1 month prediction

    # Calculate predicted revenue for December
    december_revenue = 0
    for product_pred in product_predictions:
        product_qty = product_pred['predicted_qty'][0]  # For 1 month ahead
        price = df[df['product_name'] == product_pred['product_name']]['price'].iloc[0]
        price = float(price)
        december_revenue += product_qty * price

    # Add December forecast to the result
    result['predictions'].append({
        'date': '2024-12-31',  # Static date for December forecast
        'predicted_qty': december_forecast_qty,
        'predicted_sales_count': december_forecast_sales_count,
        'predicted_revenue': december_revenue,
        'quarter': 4,  # December is in Q4
        'year': 2024
    })

    # Sort the products based on predicted revenue and quantity
    top_10_revenue_products = sorted(product_revenue_predictions, key=lambda x: sum(x['predicted_revenue']), reverse=True)[:10]
    top_10_qty_products = sorted(product_predictions, key=lambda x: sum(x['predicted_qty']), reverse=True)[:10]

    # Add top 10 products by revenue and quantity to the result
    result['top_10_revenue_products'] = [{'product_name': product['product_name'], 'predicted_revenue': sum(product['predicted_revenue'])} for product in top_10_revenue_products]
    result['top_10_qty_products'] = [{'product_name': product['product_name'], 'predicted_qty': sum(product['predicted_qty'])} for product in top_10_qty_products]

    # Slow-moving products detection: Products with predicted qty below a certain threshold (e.g., 10 units per quarter)
    slow_moving_threshold = 20
    slow_moving_products = []
    for product_pred in product_predictions:
        if sum(product_pred['predicted_qty']) < slow_moving_threshold:
            slow_moving_products.append({
                'product_name': product_pred['product_name'],
                'predicted_qty': sum(product_pred['predicted_qty'])
            })

    # Add slow-moving products to the result
    result['slow_moving_products'] = slow_moving_products

    # Return JSON response with the result
    return jsonify(convert_to_native_types(result))

if __name__ == '__main__':
    app.run(debug=True)
