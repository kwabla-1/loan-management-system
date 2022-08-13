function generalShowError(message) {
  const errorContainer = document.getElementById("formErrorContainer__profilepage");
  const errormessage = document.getElementById("formMessage");
  errorContainer.style.visibility = "visible";
  errormessage.innerText = message;

  setTimeout(() => {
      errorContainer.style.visibility = 'hidden';
      errormessage.innerText = "";

  }, 4000);
}

function generalShowSuccessMessage(message) {
  const successContainer = document.getElementById("formSuccessContainer__profilepage");
  const successMessage = document.getElementById("formMessageSuccess");
  successContainer.style.visibility = "visible";
  successMessage.innerText = message;

  setTimeout(() => {
      // document.getElementById("borrowerFormResponse").innerHTML = "";
      successContainer.style.visibility = 'hidden';
      successMessage.innerText = "";

  }, 4000);
}

function makePayment() {
    // make payments
    let paymentForm = document.getElementById('payment_form');
    let paymentFormData = document.getElementsByClassName('payment_form_data');
    let formdatapayment = new FormData();
  
  
    for (var i = 0; i < paymentFormData.length; i++) {
        formdatapayment.append(paymentFormData[i].name, paymentFormData[i].value);
  
      if (paymentFormData[i].value === "") {
        generalShowError(`${paymentFormData[i].name} is empty`);
        setTimeout(() => {document.getElementById("formErrorContainer").style.visibility = 'hidden';}, 2000);
        return ;
      }
    }

    document.getElementById("paymentbutton").disabled = true;

   //AJAX REQUEST
    let ajaxRequest1 = new XMLHttpRequest();
    ajaxRequest1.open("POST",'../../handlers/paymentHandler.php');
    ajaxRequest1.send(formdatapayment);
    document.getElementsByClassName('spinner__container')[0].style.display = 'flex';

    ajaxRequest1.onreadystatechange = function () {
      if (ajaxRequest1.readyState == 4 && ajaxRequest1.status == 200) {
          if (ajaxRequest1.responseText === "PAYMENTINSERTED") {
            document.getElementsByClassName('spinner__container')[0].style.display = 'none';
            document.getElementById("paymentbutton").disabled = false;
            paymentForm.reset();
            generalShowSuccessMessage("payment recorded successfully");
            window.location.reload();
        }else{
            console.log(ajaxRequest1.responseText);
        }
        
        //LOAD THE TABLE FOR ALL EXPENSES;
        // loadAllExpenses()
      }else if (ajaxRequest1.readyState !== 4 && ajaxRequest1.status !== 200) {
        setTimeout(() => {showError(ajaxRequest1.responseText); }, 4000);
      }
    }
}