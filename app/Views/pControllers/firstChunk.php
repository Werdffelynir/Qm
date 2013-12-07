<div class="bs-example">
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th><?php echo @$chunkVar1;?></th>
        <th><?php echo @$chunkVar2;?></th>
      </tr>
    </thead>
    <tbody>
        <?php foreach ($chunkVar4 as $chunkVar4Value) { ?> 
        <tr>
            <td>1</td>
            <?php echo '<td>'.$chunkVar4Value.'</td>'; ?> 
            <?php echo '<td>'.$chunkVar4Value.'</td>'; ?> 
        </tr>
        <?php } ?>
    </tbody>
  </table>
</div>