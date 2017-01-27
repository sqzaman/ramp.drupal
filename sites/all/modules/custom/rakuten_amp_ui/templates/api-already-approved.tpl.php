<h3 class="headline-text">APIs already approved</h3>
<table class="table table-hover">
    <thead>
    <tr>
        <th style="width: 5%;">#</th>
        <th style="width: 10%;">API</th>
        <th style="width: 60%;">Attributes</th>
        <th>Operation</th>
    </tr>
    </thead>
    <tbody>
    <?php  foreach ($apis as $key => $api): ?>
        <tr>
            <th scope="row"><div class="api-status"><?php print $key + 1; ?></div></th>
            <td><?php print $api->name; ?></td>
            <td>
                <div><code >
                <?php
                foreach ($api as $key => $value){
                    if($key != "name")
                    echo $key .": ". $value . "<br/>";
                }
                ?>

                </code></div>

            </td>
            <td>
                <div>
                    <button type="button" class="btn btn-primary btn-xs test-api" data='<?php echo json_encode($api);?>'>Test API</button>
                    <button type="button" class="btn btn-primary btn-xs delete-api" data='<?php echo json_encode($api);?>'>Delete API</button>
                </div>
            </td>

        </tr>
    <?php endforeach ?>

    </tbody>
</table>

<script>
    jQuery(function(){
        jQuery('.test-api').on('click', function(){
            var currentAPI = jQuery(this);
            var formData = jQuery(this).attr('data');
            formData = jQuery.parseJSON(formData);
            jQuery.ajax({
                url: Drupal.settings.baseUrl +"/rakuten/api/test",
                type: 'POST',
                data: formData,
                'dataType': 'json',
                success: function(data) {
                        if(data.code == "200"){
                            currentAPI.parent('div').parent('td').parent('tr').children('th').children('div.api-status').addClass('green');
                            jQuery(currentAPI).closest('tr').after("<tr class='test-result'><th></th><td></td><td><pre><code>"+ JSON.stringify(data, null, 4).replace(/\,\n$/, "") +"</code></pre></td><td></td></tr>")
                        } else {
                            currentAPI.next('div.api-status').addClass('red');
                        }
                },
                error: function(data){
                }
            });
        })


        jQuery('.delete-api').on('click', function(){
            var currentAPI = jQuery(this);
            var formData = jQuery(this).attr('data');
            formData = jQuery.parseJSON(formData);
            jQuery.ajax({
                url: Drupal.settings.baseUrl +"/rakuten/api/delete",
                type: 'POST',
                data: formData,
                'dataType': 'json',
                success: function(data) {
                    if(data.code == "204"){
                        jQuery(currentAPI).closest('tr').remove();
                        jQuery(currentAPI).next('test-result').remove();

                    } else {
                        jQuery(currentAPI).closest('tr').after("<tr><th></th><td></td><td><pre><code>"+ JSON.stringify(data, null, 4).replace(/\,\n$/, "") +"</code></pre></td><td></td></tr>")
                    }
                },
                error: function(data){
                }
            });
        })

    });



</script>

<style>
    .api-status{
        width: 20px;
        border-radius: 10px;
        text-align: center;

    }
    .green{
        background-color: green;
        color: white;
    }
    .red{
        background-color: red;
        color: white;
    }
    .left {
        float: left;
    }

</style>