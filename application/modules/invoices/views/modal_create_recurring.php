<script type="text/javascript">
    $(function () {
        // Display the create quote modal
        $('#modal_create_recurring').modal('show');

        get_recur_start_date();

        $('#recur_frequency').change(function () {
            get_recur_start_date();
        });

        // Creates the invoice
        $('#create_recurring_confirm').click(function () {
            $.post("<?php echo site_url('invoices/ajax/create_recurring'); ?>", {
                    invoice_id: <?php echo $invoice_id; ?>,
                    recur_start_date: $('#recur_start_date').val(),
                    recur_end_date: $('#recur_end_date').val(),
                    recur_frequency: $('#recur_frequency').val(),
                    recur_invoices_due_after: $('#recur_invoices_due_after').val(),
                    recur_email_invoice_template: $('#recur_email_invoice_template').val()
                },
                function (data) {
                    var response = JSON.parse(data);
                    if (response.success == '1') {
                        window.location = "<?php echo site_url('invoices/view'); ?>/<?php echo $invoice_id; ?>";
                    }
                    else {
                        // The validation was not successful
                        $('.control-group').removeClass('has-error');
                        for (var key in response.validation_errors) {
                            $('#' + key).parent().parent().addClass('has-error');
                        }
                    }
                });
        });

        function get_recur_start_date() {
            $.post("<?php echo site_url('invoices/ajax/get_recur_start_date'); ?>", {
                    invoice_date: $('#invoice_date_created').val(),
                    recur_frequency: $('#recur_frequency').val()
                },
                function (data) {
                    $('#recur_start_date').val(data);
                });
        }
    });

</script>

<div id="modal_create_recurring" class="modal col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2"
     role="dialog" aria-labelledby="modal_create_recurring" aria-hidden="true">
    <form class="modal-content">
        <div class="modal-header">
            <a data-dismiss="modal" class="close"><i class="fa fa-close"></i></a>

            <h3><?php echo lang('create_recurring'); ?></h3>
        </div>
        <div class="modal-body">

            <div class="form-group">
                <label><?php echo lang('every'); ?>: </label>

                <div class="controls">
                    <select name="recur_frequency" id="recur_frequency" class="form-control"
                            style="width: 150px;">
                        <?php foreach ($recur_frequencies as $key => $lang) { ?>
                            <option value="<?php echo $key; ?>"
                                    <?php if ($key == '1M') { ?>selected="selected"<?php } ?>><?php echo lang($lang); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group has-feedback">
                <label><?php echo lang('start_date'); ?>: </label>

                <div class="input-group">
                    <input name="recur_start_date" id="recur_start_date"
                           class="form-control datepicker">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar fa-fw"></i>
                    </span>
                </div>
            </div>

            <div class="form-group has-feedback">
                <label><?php echo lang('end_date'); ?> (<?php echo lang('optional'); ?>): </label>

                <div class="input-group">
                    <input name="recur_end_date" id="recur_end_date"
                           class="form-control datepicker">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar fa-fw"></i>
                    </span>
                </div>
            </div>
            
            <div class="form-group">
                <label for="recur_invoices_due_after" class="control-label">
                    <?php echo lang('invoices_due_after'); ?>
                </label>
                <div class="controls">
                    <input type="text" name="recur_invoices_due_after" id="recur_invoices_due_after" class="form-control"
                           value="<?php echo $this->mdl_settings->setting('invoices_due_after'); ?>">
               </div>
            </div>

            <div class="form-group">
                <label for="recur_email_invoice_template">
                    <?php echo lang('email_template'); ?>
                </label>
                <div class="controls">
                <select name="recur_email_invoice_template" id="recur_email_invoice_template" class="form-control">
                    <option value=""><?php echo lang('dont_send_email'); ?></option>
                    <?php foreach ($email_templates_invoice as $email_template) { ?>
                        <option value="<?php echo $email_template->email_template_id; ?>"><?php echo $email_template->email_template_title; ?></option>
                    <?php } ?>
                </select>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <div class="btn-group">
                <button class="btn btn-danger" type="button" data-dismiss="modal">
                    <i class="fa fa-times"></i> <?php echo lang('cancel'); ?>
                </button>
                <button class="btn btn-success" id="create_recurring_confirm" type="button">
                    <i class="fa fa-check"></i> <?php echo lang('submit'); ?>
                </button>
            </div>
        </div>

    </form>

</div>
