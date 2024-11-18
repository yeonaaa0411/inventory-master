from flask import Flask, jsonify
import pandas as pd
import mysql.connector
from sklearn.ensemble import RandomForestRegressor
import joblib
import os

app = Flask(__name__)

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

    # Fetch sales data with quantity only
    query = "SELECT date, qty FROM sales ORDER BY date"
    cursor.execute(query)
    data = cursor.fetchall()

    # Prepare data into a pandas DataFrame
    df = pd.DataFrame(data, columns=['date', 'qty'])
    df['date'] = pd.to_datetime(df['date'], errors='coerce')

    # Group by months and sum the total quantity sold (sum the quantities for the entire month)
    df = df.groupby(df['date'].dt.to_period('M'))['qty'].sum().reset_index()
    df['date'] = df['date'].dt.to_timestamp()

    # Extract features: month number, lag features, and cyclical encoding
    df['month_num'] = df['date'].dt.month + (df['date'].dt.year - df['date'].dt.year.min()) * 12
    df['month'] = df['date'].dt.month
    df['year'] = df['date'].dt.year
    df['lag_1'] = df['qty'].shift(1)  # Quantity from the previous month
    df['lag_2'] = df['qty'].shift(2)  # Quantity from 2 months ago

    # Drop rows with NaN values created by lagging
    df = df.dropna()

    # Define X and y (feature matrix and target vector)
    X = df[['month_num', 'month', 'year', 'lag_1', 'lag_2']]  # Use lag features
    y = df['qty']  # Predict total quantity sold

    # Check if model already exists
    model_path = 'quantity_forecast_model_rf.pkl'
    if os.path.exists(model_path):
        # Load the pre-trained Random Forest model
        model = joblib.load(model_path)
    else:
        # Train a new Random Forest model
        model = RandomForestRegressor(n_estimators=100, random_state=42)
        model.fit(X, y)
        # Save the model for future use
        joblib.dump(model, model_path)

    # Define future months to predict (5 months)
    future_months = 5
    current_month_num = df['month_num'].max()

    future_y = []
    future_dates = []

    # Start from the latest available month in the dataset
    last_month_data = df.iloc[-1]

    # Predict each future month
    for i in range(future_months):
        # Adjust the input data for each month to use the most recent values
        period_data = df.tail(1)  # Start with the latest data available

        # Prepare the feature matrix for the prediction
        period_X = period_data[['month_num', 'month', 'year', 'lag_1', 'lag_2']].copy()

        # Adjust lag features for the most recent data used
        # Lag 1 is the previous month's sales (which is last month's quantity)
        period_X['lag_1'] = last_month_data['qty']
        # Lag 2 is the second-to-last month's sales
        if len(df) > 1:
            period_X['lag_2'] = df['qty'].iloc[-2]
        else:
            period_X['lag_2'] = last_month_data['qty']  # Fallback to the same value if there's no second-to-last month

        # Predict the next month's sales
        month_pred = model.predict(period_X.tail(1))  # Predict using the latest data
        future_y.append(month_pred[0])

        # Calculate the future dates for each prediction
        future_date = df['date'].max() + pd.DateOffset(months=i+1)
        future_dates.append(future_date)

        # Update the 'last_month_data' with the new prediction for the next loop
        last_month_data = period_X.iloc[0].copy()
        last_month_data['qty'] = month_pred[0]

    # Prepare the final DataFrame with predicted values
    future_df = pd.DataFrame({'date': future_dates, 'predicted_sales': future_y})

    # Convert to dictionary format for JSON response
    forecast = future_df.to_dict(orient='records')

    # Close the database connection
    cursor.close()
    connection.close()

    return jsonify(forecast)

if __name__ == '__main__':
    app.run(debug=True)
