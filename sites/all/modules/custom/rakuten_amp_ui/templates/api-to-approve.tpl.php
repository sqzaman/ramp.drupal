<h3 class="headline-text">APIs to approve</h3>
<table class="table table-hover">
    <thead>
    <tr>
        <th>#</th>
        <th>API</th>
        <th>Attributes</th>
        <th>Operation</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($nids as $key => $nid): ?>
        <?php
            $node = node_load($nid, null, true);
            $item_id = $node->field_attributes['und'][0]['value'];
            $attribute_entity = entity_load('field_collection_item', array($item_id));
            $attributes = $attribute_entity[$item_id]->field_attribute['und'];
        ?>
        <tr>
            <th scope="row"><?php print $key + 1; ?></th>
            <td><?php print $node->title; ?></td>
            <td>
                <div><code >
                <?php
                    $row = array("nid" => $nid);
                     foreach ($attributes as $key => $attribute){
                         $fc = field_collection_item_load($attribute['value']);
                         echo ltrim($fc->field_attribute_key['und'][0]['value']) .": ". $fc->field_field_attribute_value['und'][0]['value'] . "<br/>";
                         $row = $row + array(ltrim($fc->field_attribute_key['und'][0]['value']) => $fc->field_field_attribute_value['und'][0]['value']);
                         //$data[] = $row;
                     }
               ?>

                </code></div>

            </td>
            <td><button type="button" class="btn btn-primary btn-xs approve-api" data='<?php echo json_encode($row);?>'>Approve</button></td>
        </tr>
    <?php endforeach ?>

    </tbody>
</table>

<script>
    jQuery(function(){
        jQuery('.approve-api').on('click', function(){
            var formData = jQuery(this).attr('data');
            var currentAPI = jQuery(this);
            formData = jQuery.parseJSON(formData);
            jQuery.ajax({
                url: Drupal.settings.baseUrl +"/rakuten/api/addtokong",
                type: 'POST',
                data: formData,
                'dataType': 'json',
                success: function(data) {
                    //if(data.code != "200"){
                        jQuery(currentAPI).closest('tr').after("<tr><th></th><td></td><td><pre><code>"+ JSON.stringify(data, null, 4) +"</code></pre></td><td></td></tr>")
                   // } else {
                    //    currentAPI.next('div.api-status').addClass('red');
                    //}
                },
                error: function(data){
                }
            });
        })

    })
</script>