<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use borales\extensions\phoneInput\PhoneInput;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
    $states = [
        "AL"=>"ALABAMA",
        "AK"=>"ALASKA",
        "AZ"=>"ARIZONA",
        "AR"=>"ARKANSAS",
        "CA"=>"CALIFORNIA",
        "CO"=>"COLORADO",
        "CT"=>"CONNECTICUT",
        "DE"=>"DELAWARE",
        "DC"=>"DISTRICT OF COLUMBIA",
        "FL"=>"FLORIDA",
        "GA"=>"GEORGIA",
        "HI"=>"HAWAII",
        "ID"=>"IDAHO",
        "IL"=>"ILLINOIS",
        "IN"=>"INDIANA",
        "IA"=>"IOWA",
        "KS"=>"KANSAS",
        "KY"=>"KENTUCKY",
        "LA"=>"LOUISIANA",
        "ME"=>"MAINE",
        "MD"=>"MARYLAND",
        "MA"=>"MASSACHUSETTS",
        "MI"=>"MICHIGAN",
        "MN"=>"MINNESOTA",
        "MS"=>"MISSISSIPPI",
        "MO"=>"MISSOURI",
        "MT"=>"MONTANA",
        "NE"=>"NEBRASKA",
        "NV"=>"NEVADA",
        "NH"=>"NEW HAMPSHIRE",
        "NJ"=>"NEW JERSEY",
        "NM"=>"NEW MEXICO",
        "NY"=>"NEW YORK",
        "NC"=>"NORTH CAROLINA",
        "ND"=>"NORTH DAKOTA",
        "OH"=>"OHIO",
        "OK"=>"OKLAHOMA",
        "OR"=>"OREGON",
        "PA"=>"PENNSYLVANIA",
        "RI"=>"RHODE ISLAND",
        "SC"=>"SOUTH CAROLINA",
        "SD"=>"SOUTH DAKOTA",
        "TN"=>"TENNESSEE",
        "TX"=>"TEXAS",
        "UT"=>"UTAH",
        "VT"=>"VERMONT",
        "VA"=>"VIRGINIA",
        "VI"=>"VIRGIN ISLANDS",
        "WA"=>"WASHINGTON",
        "WV"=>"WEST VIRGINIA",
        "WI"=>"WISCONSIN",
        "WY"=>"WYOMING" ,
    ];?>

<div class="customer-form">
    <?php $customerForm = ActiveForm::begin([
        'id' => 'initial-customer-form',
        'action' => \yii\helpers\Url::to(['/customer/initial-create']),
    ]) ?>
    <?= $customerForm->field($model, 'firstName')->label(Yii::t('app', 'First Name'))->textInput()?>
    <?= $customerForm->field($model, 'lastName')->label(Yii::t('app', 'Last Name'))->textInput()?>
    <?= $customerForm->field($model, 'phoneNumber')->label(Yii::t('app', 'Phone Number'))->widget(PhoneInput::class, [
            'id' => 'customerPhonenumber',
            //'name' => 'Customer[phone_number]',
            'jsOptions' => [
                'allowExtensions' => true,
            ]
        ])?>
    <?= $customerForm->field($model, 'streetAddress')->label(Yii::t('app', 'Street Address'))->textInput()?>
    <?= $customerForm->field($model, 'city')->label(Yii::t('app', 'City'))->textInput()?>
    <?= $customerForm->field($model, 'state')->label(Yii::t('app', 'State'))->widget(Select2::class, [
        'data' => $states,
        'options' => ['placeholder' => Yii::t('app', 'Select a state ...')],
        'pluginOptions' => [
            'allowClear' => true,
            'dropdownParent' => '#modalNewCustomer'
        ],
    ])?>
    <?= $customerForm->field($model, 'zip')->label(Yii::t('app', 'Zip'))->textInput()?>
    <?= Html::submitButton('<span class="fa fa-upload" aria-hidden="true"></span> ' . Yii::t('app', 'Upload'), [
        'class'             => 'btn btn-success',
    ])?>
    <?php $customerForm = ActiveForm::end() ?>   
</div>

<?php
if ($change_form) {
    $ajaxSubmitUrl = yii\helpers\Url::to(['/customer/ajax-initial-create']);
    $jsBlock2 = <<< JS
        $("#initial-customer-form").on("beforeSubmit", function(){
            let data = $("#initial-customer-form").serialize();
            console.log(data);
            console.log('$ajaxSubmitUrl');
            $.ajax({
                url:'$ajaxSubmitUrl',
                type: "POST",
                data: data,
                success: function (returnData) {
                    console.log(returnData);
                    if(returnData != 400) {
                        returnData = JSON.parse(returnData);
                        let newOption = new Option(returnData.text, returnData.id, false, false);
                        //change the forms
                        $("#customer_id").append(newOption).trigger("change");
                        $("#customer_id").val(returnData.id).trigger("select2:select");
                        //clear form
                        $("#initial-customer-form")[0].reset();
                        $("#modalNewCustomer").modal("hide");
                        //updateAutomobiles();
                        
                    } else {
                        console.log(returnData);
                        console.log("Error")
                    }

                }, error: function( xhr, status, errorThrown ) {
                    console.log("Error: " + errorThrown );
                    console.log("Status: " + status );
                    console.dir( xhr );
                },
            })
        }).on("submit", function(e){
            e.preventDefault();
        });

    JS;
    $this->registerJs($jsBlock2, \yii\web\View::POS_END);
}
?>
