<?php require_once PUBLIC_PATH . "admin/template/header.php";?>
    <div class="col-6 mx-auto my-5 border border-info">
        <form accept-charset="UTF-8" action="/" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="pk_bQQaTxnaZlzv4FnnuZ28LFHccVSaj" id="payment-form" method="post"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="✓" /><input name="_method" type="hidden" value="PUT" />
            <div class='form-row'>
              <div class='col-12 form-group required text-center'>
                <label class='control-label d-inline-flex align-items-center' id="label-code">Code Donate
                    <i class="ti-info-alt ml-2">
                        <img src="<?php echo ASSETS_URL;?>/img/codemomo.jpg" class="img-thumbnail momo-img" alt="codemomo.jpg">
                    </i>
                </label>
                <input class='form-control' name="code" type='text'>
              </div>
            </div>
            <div class='form-row'>
              <div class='col-12 form-group'>
                <button class='form-control btn btn-primary' type='submit'>Check »</button>
              </div>
            </div>
        </form>
    </div>
    <div class="border border-success mb-2"></div>
    <div class="col-md-8 col-xl-8 col-sm-12 mx-auto" id="info-order">
        
    </div>
    </div>
<script>
    $('button[type="submit"]').click((e) =>{
        e.preventDefault();
        let id = $('input[name="code"]').val();
        if(!id)
            return;
        $.get("/ajaxAdmin/code/"+id,
            function (res) {
                if(res.status !== 200)
                {
                    toastr.error(res.message);
                    return;
                }
                let data = res.data;
                let note = data.message;
                if(data.errorCode == 58)
                {
                    toastr.error(note);
                    return;
                }
                toastr.success(res.message);
                let code = data.orderId;
                let trans_id = data.transId;
                let type = 'momo';
                let amount = data.amount;
                let email = data.extraData.split('=')[1];
                let className = data.errorCode == 0 ? 'success' : 'danger';
                let name = data.errorCode == 0 ? 'Success' : 'Cancel';
                $('#info-order').html(`<b class="d-inline-block" style="width:130px;">Code Donate:</b><span>${code}</span><br>
        <b class="d-inline-block" style="width:130px;">Transaction ID:</b> <span>${trans_id}</span><br>
        <b class="d-inline-block" style="width:130px;">Type:</b> <span>${type}</span><br>
        <b class="d-inline-block" style="width:130px;">Amount:</b> <span>${amount}</span><br>
        <b class="d-inline-block" style="width:130px;">Email:</b> <span>${email}</span><br>
        <b class="d-inline-block" style="width:130px;">Status:</b> <span><div class="badge badge-${className}">${name}</div></span><br>
        <b class="d-inline-block" style="width:130px;">Note:</b> <span>${note}</span><br>`);
            },
            "json"
        );
    });
</script>
<?php require_once PUBLIC_PATH . "admin/template/footer.php";?>
