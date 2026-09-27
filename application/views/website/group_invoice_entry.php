<div class="container text-center">    
    <div class="row">
        <div class="col-sm-12">
            <div class="well">
                <h3>Group Invoice Editor</h3>
                <p>Editing <?php echo count($invoice_data); ?> records</p>
                
                <form id="group_invoice_form" action="#">
                    <input type="hidden" name="ids" value="<?php echo $ids; ?>">
                    
                    <div class="row">
                        <div class="form-group col-sm-3">
                            <label for="garden_id">Garden Name</label>
                            <select class="form-control input-sm" id="garden_id" name="garden_id">
                                <option value="">-- Keep Current --</option>
                                <?php foreach($garden_data as $garden): ?>
                                    <option value="<?php echo $garden['id']; ?>"><?php echo $garden['garden_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="w_house_id">Warehouse</label>
                            <select class="form-control input-sm" id="w_house_id" name="w_house_id">
                                <option value="">-- Keep Current --</option>
                                <?php foreach($warehouse_data as $warehouse): ?>
                                    <option value="<?php echo $warehouse['id']; ?>"><?php echo $warehouse['short_key_word']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="invoice_group_txt">Invoice Group Text</label>
                            <input type="text" class="form-control input-sm text-uppercase" id="invoice_group_txt" name="invoice_group_txt" placeholder="Keep current if empty">
                        </div>

                         <div class="form-group col-sm-3">
                            <label for="invoice_group">Invoice Group From</label>
                            <input type="number" class="form-control input-sm" id="invoice_group" name="invoice_group" placeholder="Keep current if empty">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label for="invoice_group_upto">Invoice Group To</label>
                            <input type="number" class="form-control input-sm" id="invoice_group_upto" name="invoice_group_upto" placeholder="Keep current if empty">
                        </div>
                         <div class="form-group col-sm-4">
                            <label for="old_season">Old Season</label>
                            <select class="form-control input-sm" id="old_season" name="old_season">
                                <option value="">-- Keep Current --</option>
                                <?php foreach($season_data as $season): ?>
                                    <option value="<?php echo $season['season_name']; ?>"><?php echo $season['season_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-4">
                            <label style="visibility: hidden;">Submit</label><br>
                            <input type="submit" name="Submit" id="Submit" value="Update All Records" class="btn btn-success">
                        </div>
                    </div>
                </form>
                
                <hr>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Lot No</th>
                                <th>Invoice</th>
                                <th>Current Garden</th>
                                <th>Current Warehouse</th>
                                <th>Current Inv Group</th>
                                <th>Current Old Season</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($invoice_data as $invoice): ?>
                            <tr>
                                <td><?php echo $invoice['lot_no']; ?></td>
                                <td><?php echo $invoice['w_inv_no']; ?></td>
                                <td><?php echo $invoice['garden_name']; ?></td>
                                <td><?php echo $invoice['short_key_word']; ?></td>
                                <td class="text-uppercase"><?php echo $invoice['invoice_group_txt'] . ' ' . $invoice['invoice_group'] . '-' . $invoice['invoice_group_upto']; ?></td>
                                <td><?php echo $invoice['old_season']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#group_invoice_form').submit(function(e) {
        e.preventDefault();
        
        // Show loading state
        $('#Submit').val('Updating...');
        $('#Submit').prop('disabled', true);
        
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/update_group_invoice_info'); ?>",
            dataType: "JSON",
            data: $(this).serialize(),
            success: function(response) {
                if (response.status == 'success') {
                    alert(response.message);
                    window.history.back();
                } else {
                    alert('Error: ' + response.message);
                    $('#Submit').val('Update All Records');
                    $('#Submit').prop('disabled', false);
                }
            },
            error: function() {
                alert('An error occurred during the update.');
                $('#Submit').val('Update All Records');
                $('#Submit').prop('disabled', false);
            }
        });
    });

    $("#garden_id").select2();
    $("#w_house_id").select2();
});
</script>