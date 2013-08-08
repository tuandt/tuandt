<div class="god">
    <p>
        <?php echo lang('soslaundry:register_form_title');?>
    </p>
    <p>
        <?php echo lang('soslaundry:register_form_text');?>
    </p>
    <h1 class="luck">
        <?php echo lang('soslaundry:register_form_good_luck');?>
    </h1>
</div>
<form action="<?php echo base_url('soslaundry/form');?>" method="post" id="register-form" enctype="multipart/form-data" accept-charset="utf-8">
    <div class="message" id="messages">
        <?php
        if(isset($_GET['message'])){
            $code = (int) $_GET['message'];
            if($code == 1){
                $msg = lang('soslaundry:register_success');
            }elseif($code == 2){
                $msg = lang('soslaundry:register_error');
            }elseif($code == 3){
                $msg = lang('soslaundry:register_email_exist_error');
            }elseif($code == 4){
                $msg = lang('soslaundry:register_email_format_error');
            }elseif($code == 5){
                $msg = lang('soslaundry:register_phone_error');
            }else{
                $msg = '';
            }
            ?>
            <?php if($code == 1){?>
                <span style="font-size: 16px;color: green;"><?php echo $msg;?></span>
            <?php }else{?>
                <span style="font-size: 16px;color: red;"><?php echo $msg;?></span>
            <?php }?>
        <?php }?>
    </div>
    <div class="fieldset name">
        <div class="first_name">
            <label><?php echo lang('soslaundry:name'); ?></label><br />
            <input id="txtFirstName" type="text" name="first_name"/><br />
            <label class="note"><?php echo lang('soslaundry:first_name'); ?></label>
        </div>
        <div class="last_name">
            <label>&nbsp;</label><br />
            <input id="txtLastName" type="text" name="last_name"/><br />
            <label class="note"><?php echo lang('soslaundry:last_name'); ?></label>
        </div>
    </div>
    <div class="fieldset">
        <div class="email">
            <label><?php echo lang('soslaundry:email'); ?></label><br />
            <input id="txtEmail" type="email" name="email"/>
        </div>
        <div class="mobile">
            <label><?php echo lang('soslaundry:phone'); ?></label><br />
            <input id="txtPhone1" type="text" maxlength="3" name="phone[]"/>
            <label class="note">-</label>
            <input id="txtPhone2" type="text" maxlength="3" name="phone[]"/>
            <label class="note">-</label>
            <input id="txtPhone3" type="text" maxlength="4" name="phone[]"/>
        </div>
    </div>
    <div class="fieldset">
        <div class="choose_hotel">
            <select name="hotel" class="select_hotel">
                <option value="0"><?php echo lang('soslaundry:choose_hotel'); ?></option>
                <option value="0"><?php echo lang('soslaundry:choose_hotel'); ?></option>
                <?php if(!empty($hotels)):?>
                    <?php foreach($hotels as $hotel):?>
                        <option value="<?php echo $hotel->id;?>"><?php echo $hotel->name;?></option>
                    <?php endforeach;?>
                <?php endif;?>
            </select>
        </div>
        <div class="agree">
            <input type="checkbox" name="rule" id="txtRule"><label for="txtRule">Agree to the rules. </label>
            <br/>
            <input id="saveForm" name="saveForm" type="submit" value="<?php echo lang('soslaundry:submit_label'); ?>" />
        </div>
    </div>
</form>
<div class="clear">&nbsp;</div>
<script type="text/javascript">
    $(function(){
        $('#txtPhone1').blur(function(e) {
            e.stopPropagation();
            if (validatePhone('txtPhone1')) {
                $('#txtPhone1').css('border', '1px solid green');
                var phone1 = $("#txtPhone1").val();
                if(phone1.length < 3){
                    $('#txtPhone1').css('border', '1px solid red');
                    $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:phone1_length'); ?></span>');
                }else{
                    $("#messages").html("<span></span>");
                }
            }
            else {
                $('#txtPhone1').css('border', '1px solid red');
                $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:phone_regex'); ?></span>');
            }
        });
        $('#txtPhone2').blur(function(e) {
            e.stopPropagation();
            if (validatePhone('txtPhone2')) {
                $('#txtPhone2').css('border', '1px solid green');
                var phone2 = $("#txtPhone2").val();
                if(phone2.length < 3){
                    $('#txtPhone2').css('border', '1px solid red');
                    $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:phone2_length'); ?></span>');
                }else{
                    $("#messages").html("<span></span>");
                }
            }
            else {
                $('#txtPhone2').css('border', '1px solid red');
                $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:phone_regex'); ?></span>');
            }
        });
        $('#txtPhone3').blur(function(e) {
            e.stopPropagation();
            if (validatePhone('txtPhone3')) {
                $('#txtPhone3').css('border', '1px solid green');
                var phone3 = $("#txtPhone3").val();
                if(phone3.length < 4){
                    $('#txtPhone3').css('border', '1px solid red');
                    $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:phone3_length'); ?></span>');
                }else{
                    $("#messages").html("<span></span>");
                }
            }
            else {
                $('#txtPhone3').css('border', '1px solid red');
                $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:phone_regex'); ?></span>');
            }
        });

        $('#register-form').submit(function() {
            var submitform = true;
            if (validateEmpty('txtFirstName')) {
                $('#txtFirstName').css('border', '1px solid green');
                submitform = submitform && true;
            }
            else {
                $('#txtFirstName').css('border', '1px solid red');
                $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:firstname_empty'); ?></span>');
                return false;
            }
            if (validateEmpty('txtLastName')) {
                $('#txtLastName').css('border', '1px solid green');
                submitform = submitform && true;
            }
            else {
                $('#txtLastName').css('border', '1px solid red');
                $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:lastname_empty'); ?></span>');
                return false;
            }
            if (validateEmpty('txtEmail')) {
                $('#txtEmail').css('border', '1px solid green');
                submitform = submitform && true;
            }
            else {
                $('#txtEmail').css('border', '1px solid red');
                $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:email_empty'); ?></span>');
                return false;
            }
            if (validateEmpty('txtPhone1')) {
                $('#txtPhone1').css('border', '1px solid green');
                if (validatePhone('txtPhone1')) {
                    $('#txtPhone1').css('border', '1px solid green');
                    var phone1 = $("#txtPhone1").val();
                    if(phone1.length < 3){
                        $('#txtPhone1').css('border', '1px solid red');
                        $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:phone1_length'); ?></span>');
                        return false;
                    }else{
                        $("#messages").html("<span></span>");
                    }
                }
                else {
                    $('#txtPhone1').css('border', '1px solid red');
                    $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:phone_regex'); ?></span>');
                    return false;
                }
            }
            else {
                $('#txtPhone1').css('border', '1px solid red');
                $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:phone_empty'); ?></span>');
                return false;
            }
            if (validateEmpty('txtPhone2')) {
                $('#txtPhone2').css('border', '1px solid green');
                if (validatePhone('txtPhone2')) {
                    $('#txtPhone2').css('border', '1px solid green');
                    var phone2 = $("#txtPhone2").val();
                    if(phone2.length < 3){
                        $('#txtPhone2').css('border', '1px solid red');
                        $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:phone2_length'); ?></span>');
                        return false;
                    }else{
                        $("#messages").html("<span></span>");
                    }
                }
                else {
                    $('#txtPhone2').css('border', '1px solid red');
                    $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:phone_regex'); ?></span>');
                    return false;
                }
            }
            else {
                $('#txtPhone2').css('border', '1px solid red');
                $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:phone_empty'); ?></span>');
                return false;
            }
            if (validateEmpty('txtPhone3')) {
                $('#txtPhone3').css('border', '1px solid green');
                if (validatePhone('txtPhone3')) {
                    $('#txtPhone3').css('border', '1px solid green');
                    var phone3 = $("#txtPhone3").val();
                    if(phone3.length < 4){
                        $('#txtPhone3').css('border', '1px solid red');
                        $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:phone3_length'); ?></span>');
                        return false;
                    }else{
                        $("#messages").html("<span></span>");
                    }
                }
                else {
                    $('#txtPhone3').css('border', '1px solid red');
                    $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:phone_regex'); ?></span>');
                    return false;
                }
            }
            else {
                $('#txtPhone3').css('border', '1px solid red');
                $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:phone_empty'); ?></span>');
                return false;
            }
            if(validHotel() == false){
                $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:hotel_valid'); ?></span>');
                return false;
            }
            if(validRule()){
                $('#txtRule').css('border', '1px solid green');
                submitform = submitform && true;
            }else{
                $('#txtRule').css('border', '1px solid red');
                $("#messages").html('<span style="font-size: 16px;color: red;"><?php echo lang('soslaundry:rule_accept'); ?></span>');
                return false;
            }
            return submitform;
        });
    });
    function validatePhone(txtPhone) {
        var a = document.getElementById(txtPhone).value;
        var filter = /^[0-9-+]+$/;
        if (filter.test(a)) {
            return true;
        }
        else {
            return false;
        }
    }
    function validateEmpty(txtField) {
        var a = document.getElementById(txtField).value;
        if (a == null || a == "") {
            return false;
        }
        else {
            return true;
        }
    }
    function validRule(){
        return document.getElementById("txtRule").checked;
    }
    function validHotel(){
        var e  = document.getElementById("txtHotel");
        var hotel = e.options[e.selectedIndex].value;
        if(hotel != null && hotel != '' && hotel != '0' && hotel != 0){
            return true;
        }
        return false;
    }
</script>