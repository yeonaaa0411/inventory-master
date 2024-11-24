<?php
  $page_title = 'All logs';
  require_once('./includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);



$logs = find_all('log');


?>
<?php include_once('./layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<!--     *************************     -->

  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">

        <div class="panel-heading clearfix">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Logged Actions</span>
          </strong>
   
        </div>

        <div class="panel-body">
<!--     *************************     -->
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
	<th class="text-center" style="width: 15%;"> id  </th>
	<th class="text-center" style="width: 15%;"> user_id  </th>
	<th class="text-center" style="width: 15%;"> remote_ip  </th>
	<th class="text-center" style="width: 15%;"> action </th>
	<th class="text-center" style="width: 15%;"> date  </th>
	<th class="text-center" style="width: 15%;"> Actions </th>

</tr>
</thead>
<tbody>


<?php
foreach ($logs as $log )
{
?>
<tr>
<td class="text-center">
<?php echo $log['id']; ?>
</td>

<td class="text-center">
<?php echo $log['user_id']; ?>
</td>
<td class="text-center">
<?php echo $log['remote_ip']; ?>
</td>
<td class="text-center">
<?php echo $log['action']; ?>
</td>

<?php
//$category = find_by_id("categories",$setting['category_id']);
//echo $category['name'];
?>


<td class="text-center">
<?php echo $log['date']; ?>
</td>


               <td class="text-center">
                  <div class="btn-group">
                     <a href="delete_log.php?id=<?php echo $log['id']; ?>" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                       <span class="glyphicon glyphicon-trash"></span>
                     </a>
                  </div>
               </td>
             </tr>
<?php
}
?>

           </tbody>
         </table>
<!--     *************************     -->
        </div>
      </div>

    </div>
  </div>
<?php include_once('./layouts/footer.php'); ?>
