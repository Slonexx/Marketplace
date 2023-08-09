<script>

    function ajax_settings(url, method, data){
        return {
            "url": url,
            "method": method,
            "timeout": 0,
            "headers": {"Content-Type": "application/json",},
            "data": data,
        }
    }

    function FU_AnimationDownloadGIF(type, name){
        window.document.getElementById('HeaderGIF').innerText = name
        if (type === 'show') {
            $('#GIF').modal('show')
        }
        if (type === 'hide') {
            $('#GIF').modal('hide')
        }
        if (type === 'toggle') {
            $('#GIF').modal('toggle')
        }
        if (type === 'dispose') {
            $('#GIF').modal('dispose')
        }
    }

    function payment_type_on_set_option(type, price){
        window.document.getElementById('valueSelector').value = type

        let Cash = window.document.getElementById('Visibility_Cash')
        let Card = window.document.getElementById('Visibility_Card')

        let input_cash = window.document.getElementById('cash')
        let input_card = window.document.getElementById('card')

        input_cash.value = ''
        input_card.value = ''
        Cash.style.display = 'none'
        Card.style.display = 'none'

        console.log(type);

        switch (type) {
            case 0 : {
                Cash.style.display = 'block'
                input_cash.value = price
                break
            }
            case 1 : {
                Card.style.display = 'block'
                input_card.value = price
                input_card.disabled = true
                break
            }
            default: {
                console.log(type)
            }

        }
    }

    function ShowCheck(){
        let urlrekassa = 'https://app.rekassa.kz/'
        //let url = 'http://rekassa/Popup/customerorder/closeShift';
        let url = 'https://smartkaspi.kz/api/ticket';
        let data = {
            accountId: accountId,
            id_ticket: id_ticket,
        };

        let settings = ajax_settings(url, "GET", data);
        console.log(url + ' settings ↓ ')
        console.log(settings)

        $.ajax(settings).done(function (response) {
            console.log(url + ' response ↓ ')
            console.log(response)
            window.open(urlrekassa + response);
        })
    }

    function SelectorSum(Selector){
        window.document.getElementById("cash").value = ''
        window.document.getElementById("card").value = ''
        window.document.getElementById("mobile").value = ''
        let option = Selector.options[Selector.selectedIndex];
        if (option.value === "0") {
            document.getElementById('Visibility_Cash').style.display = 'block';
            document.getElementById('Visibility_Card').style.display = 'none';
            document.getElementById('Visibility_Mobile').style.display = 'none';
        }
        if (option.value === "1") {
            document.getElementById('Visibility_Card').style.display = 'block';
            document.getElementById('Visibility_Cash').style.display = 'none';
            document.getElementById('Visibility_Mobile').style.display = 'none';
            let card =  window.document.getElementById("card");
            card.value = window.document.getElementById("sum").innerText
            window.document.getElementById("card").disabled = true
        }
        if (option.value === "2") {
            document.getElementById('Visibility_Cash').style.display = 'none';
            document.getElementById('Visibility_Card').style.display = 'none';
            document.getElementById('Visibility_Mobile').style.display = 'block';
            let mobile =  window.document.getElementById("mobile");
            mobile.value = window.document.getElementById("sum").innerText
            window.document.getElementById("mobile").disabled = true
        }
        if (option.value === "3") {
            document.getElementById('Visibility_Cash').style.display = 'block';
            document.getElementById('Visibility_Card').style.display = 'block';
            document.getElementById('Visibility_Mobile').style.display = 'block';
            window.document.getElementById("card").disabled = false
            window.document.getElementById("mobile").disabled = false
        }

    }

    function updateQuantity(id, params){
        let object_Quantity = window.document.getElementById('productQuantity_'+id);
        let Quantity = parseInt(object_Quantity.innerText)

        if (Quantity >= 0 ){

            let object_price = window.document.getElementById('productPrice_'+id).innerText;
            let object_Final = window.document.getElementById('productFinal_'+id);

            let object_sum = window.document.getElementById('sum');
            let sum = parseFloat(object_sum.innerText - object_Final.innerText)

            if (params === 'plus'){
                object_Quantity.innerText = Quantity + 1
                object_Final.innerText = object_Quantity.innerText * object_price
                object_sum.innerText = parseFloat(sum + parseFloat(object_Final.innerText))
            }
            if (params === 'minus'){
                object_Quantity.innerText = Quantity - 1
                object_Final.innerText = object_Quantity.innerText * object_price
                object_sum.innerText = parseFloat(sum + parseFloat(object_Final.innerText))
                if (parseInt(object_Quantity.innerText) === 0){
                    deleteBTNClick( id )
                }
            }
        } else deleteBTNClick( id )

    }



    function newPopup(){
        window.document.getElementById("sum").innerHTML = ''
        window.document.getElementById("main").innerHTML = ''

        window.document.getElementById("message").style.display = "none"
        window.document.getElementById("messageGood").style.display = "none"
        window.document.getElementById("closeButtonId").style.display = "none"

        window.document.getElementById("refundCheck").style.display = "none"
        window.document.getElementById("getKKM").style.display = "none"
        window.document.getElementById("ShowCheck").style.display = "none"

        window.document.getElementById("cash").value = ''
        window.document.getElementById("card").value = ''
        window.document.getElementById("mobile").value = ''

        window.document.getElementById("cash").style.display = "block"
        let thisSelectorSum = window.document.getElementById("valueSelector")
        thisSelectorSum.value = 0;
        SelectorSum(thisSelectorSum)
    }


    function formatParams(params) {
        return "?" + Object
            .keys(params)
            .map(function (key) {
                return key + "=" + encodeURIComponent(params[key])
            })
            .join("&")
    }


    function option_value_error_fu(index_option, money_card, money_cash, money_mobile){
        console.log(index_option)
        let params = false
        switch (index_option) {
            case 0 && "0": {
                if (!money_cash) {
                    window.document.getElementById('messageAlert').innerText = 'Вы не ввели сумму наличных'
                    window.document.getElementById('message').style.display = "block"
                    params = true
                }
                break
            }
            case 1 && "1": {
                if (!money_cash) {
                    window.document.getElementById('messageAlert').innerText = 'Вы не ввели сумму карты'
                    window.document.getElementById('message').style.display = "block"
                    params = true
                }
                break
            }
            case 2 && "2": {
                if (!money_mobile){
                    window.document.getElementById('messageAlert').innerText = 'Вы не ввели сумму мобильных'
                    window.document.getElementById('message').style.display = "block"
                    params = true
                }
                break
            }
            case 3 && "3": {
                if (!money_card && !money_cash && !money_mobile){
                    window.document.getElementById('messageAlert').innerText = 'Вы не ввели сумму'
                    window.document.getElementById('message').style.display = "block"
                    params = true
                }
                break
            }
            default: {

            }

        }
        return params
    }

    function roundToTwo(num) { return +(Math.round(num + "e+2")  + "e-2"); }

    function isNumberKeyCash(evt){
        let charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode == 46){
            let inputValue = $("#cash").val();
            let count = (inputValue.match(/'.'/g) || []).length;
            if(count<1){
                return inputValue.indexOf('.') < 1;

            }else{
                return false;
            }
        }
        return !(charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57));

    }
    function isNumberKeyCard(evt){
        let charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode == 46){
            let inputValue = $("#card").val();
            let count = (inputValue.match(/'.'/g) || []).length;
            if(count<1){
                return inputValue.indexOf('.') < 1;

            }else{
                return false;
            }
        }
        return !(charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57));

    }
    function isNumberKeyMobile(evt){
        let charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode == 46){
            let inputValue = $("#mobile").val();
            let count = (inputValue.match(/'.'/g) || []).length;
            if(count<1){
                return inputValue.indexOf('.') < 1;

            }else{
                return false;
            }
        }
        return !(charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57));

    }

    function deleteBTNClick(Object){
        let sum = document.getElementById("sum").innerHTML;
        let final = document.getElementById('productFinal_' + Object).innerHTML;
        window.document.getElementById("sum").innerHTML = toString(parseFloat(sum)-parseFloat(final));

        window.document.getElementById('productName_' + Object).innerHTML = '';
        window.document.getElementById('productQuantity_' + Object).innerHTML = '';
        window.document.getElementById('productPrice_' + Object).innerHTML = '';
        window.document.getElementById('productVat_' + Object).innerHTML = '';
        window.document.getElementById('productDiscount_' + Object).innerHTML = '';
        window.document.getElementById('productFinal_' + Object).innerHTML = '';
        window.document.getElementById(Object).style.display = "none";
    }

    function closeShift(){

        let pinCode = window.document.getElementById('pin_code').value;

        let params = {
            accountId: accountId,
            pincode: pinCode,
        };
        let url = 'https://smartkaspi.kz/Popup/customerorder/closeShift';
        let settings = ajax_settings(url, "GET", params);
        console.log(url + ' settings ↓ ')
        console.log(settings)

        $.ajax(settings).done(function (json) {
            console.log(url + ' response ↓ ')
            console.log(json)

            if (json.statusCode === 200){
                window.document.getElementById('messageAlert').innerText = json.message;
                window.document.getElementById('message').style.display = "block";
            } else {
                console.log(' Error = ' + url + ' message = ' + JSON.stringify(json.message))
                window.document.getElementById('messageAlert').innerText = "ошибка";
                window.document.getElementById('message').style.display = "block";
            }
        })
    }
</script>
