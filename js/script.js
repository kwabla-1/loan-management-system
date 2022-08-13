const list = document.querySelectorAll('.list');
let dropdown = document.getElementsByClassName('dropdown_button');
let dropdown2 = document.getElementsByClassName('dropdown_button2');

let dropdownContent = document.getElementsByClassName("dropdownContent");
let dropdownContent2 = document.getElementsByClassName("dropdownContent2");

let arrorRight = document.getElementsByClassName("arrowRight");
let arrorRight2 = document.getElementsByClassName("arrowRight2");


const toggleModel = document.getElementById("recommend");
const reButton = document.getElementsByClassName("recommendButtom");


const toggleModel1 = document.getElementById("recommend1");// delete after code below works;
const changeButtons = document.getElementsByClassName("changeButton");

const closeButton = document.getElementById("closeModal");
const closeButton1 = document.getElementById("closeModal1");
const closeButton2 = document.getElementById("closeModal2");

const modalContainer = document.getElementsByClassName("model__container");
const modalContainer1 = document.getElementsByClassName("model__container1");
const modalContainer2 = document.getElementsByClassName("model__container2");
const borrowerTable = document.getElementById("borrowerTable").getElementsByTagName('tbody')[0];
const accessmentTable = document.getElementById("accessmentTable").getElementsByTagName('tbody')[0];
const expenseTable = document.getElementById("expenseTable").getElementsByTagName('tbody')[0];

const disbursementTable = document.getElementById("disbursementTable").getElementsByTagName('tbody')[0];
const customerTable = document.getElementById("allCustomerTable").getElementsByTagName('tbody')[0];
const chronicTable = document.getElementById("chronicTable").getElementsByTagName('tbody')[0];
const employeeTable = document.getElementById("employeeTable").getElementsByTagName('tbody')[0];
const blacklisttable = document.getElementById("blacklisttable").getElementsByTagName('tbody')[0];
const searchResultContainer = document.getElementById('liveSearchData');
const searchInputFiedld = document.getElementById('searchTextInput');


// ============================  DISPLAY INPUT FIELD ==================================
function showInputField(nameSelect) {
    if (nameSelect.value == "momo") {

        const fieldform = document.getElementById('momoDiv');
        const momoInput = document.getElementById('momoNumber');
        //removing the momo input;
        if (momoInput) {
            fieldform.removeChild(document.getElementById('momoNumber'));
            fieldform.removeChild(document.getElementById('momoNumber2'));
            console.log('removed the momo input');
        }

        //REMOVING THE ACCOUNT INPUTS IF ITS THERE
        const fieldform1 = document.getElementById('accountHolderDiv');
        const accountNameInputfield = document.getElementById('accountName');
        const accountNumberInputfield = document.getElementById('accountNUmber');
        if (accountNameInputfield && accountNumberInputfield) {
            fieldform1.removeChild(document.getElementById('accountName')) ;
            fieldform1.removeChild(document.getElementById('accountNUmber')) ;
            console.log('removed the accounts inputs FROM THE MOMO SIDE');
        }

        //ADDING THE MOMO INPUT FIELD
        const momoNumberinput = document.createElement("input");
        momoNumberinput.placeholder = "Enter Momo Number"
        momoNumberinput.setAttribute('type','number');
        momoNumberinput.setAttribute('id', 'momoNumber');
        momoNumberinput.setAttribute('name', 'momoNumber');
        momoNumberinput.className= "borrower_data";
        fieldform.appendChild(momoNumberinput);

        //ADDING THE SECOND MOMO INPUT FIELD
        const momoNumberinput2 = document.createElement("input");
        momoNumberinput2.placeholder = "Confirm mobile number"
        momoNumberinput2.setAttribute('type','number');
        momoNumberinput2.setAttribute('id', 'momoNumber2');
        momoNumberinput2.setAttribute('name', 'momoNumberagain');
        momoNumberinput2.className= "borrower_data";
        fieldform.appendChild(momoNumberinput2);

        document.getElementById("momoDiv").style.display = "block";
        document.getElementById("accountHolderDiv").style.display = "none";

    }else if (nameSelect.value == "account") {

        const fieldform1 = document.getElementById('accountHolderDiv');
        //REMOVING THE ACCOUNT INPUTS IF ITS THERE
        const accountNameInputfield = document.getElementById('accountName');
        const accountNumberInputfield = document.getElementById('accountNUmber');
        if (accountNameInputfield && accountNumberInputfield) {
            fieldform1.removeChild(document.getElementById('accountName')) ;
            fieldform1.removeChild(document.getElementById('accountNUmber')) ;
            console.log('removed the accounts inputs');
        }

        //REMOVEING THE MOMO INPUT FIELDS;
        const fieldform = document.getElementById('momoDiv');
        const momoInput = document.getElementById('momoNumber');
        if (momoInput) {
            fieldform.removeChild(document.getElementById('momoNumber'));
            fieldform.removeChild(document.getElementById('momoNumber2'));
            console.log('removed the momo input FROM ACCOUNT SIDE');
        }

        //ADDING THE ACCOUNT INPUT FIELDS
        const accountNameInput = document.createElement("input");
        accountNameInput.placeholder = "Enter account name"
        accountNameInput.setAttribute('type','text');
        accountNameInput.setAttribute('id','accountName');
        accountNameInput.setAttribute('name', 'accountName');
        accountNameInput.className= "borrower_data";

        const accountNumberInput = document.createElement("input");
        accountNumberInput.placeholder = "Enter account name"
        accountNumberInput.setAttribute('type','number');
        accountNumberInput.setAttribute('id','accountNUmber');
        accountNumberInput.setAttribute('name', 'accountNumber');
        accountNumberInput.className = "borrower_data";


        fieldform1.appendChild(accountNameInput);
        fieldform1.appendChild(accountNumberInput);

        document.getElementById("accountHolderDiv").style.display = "block";
        document.getElementById("momoDiv").style.display = "none";

    }else{
        document.getElementById("accountHolderDiv").style.display = "none";
        document.getElementById("momoDiv").style.display = "none";

        //removing the momo input;
        const fieldform = document.getElementById('momoDiv');
        const momoInput = document.getElementById('momoNumber');
        if (momoInput) {
            fieldform.removeChild(document.getElementById('momoNumber'));
            fieldform.removeChild(document.getElementById('momoNumber2'));
            console.log('removed the momo input');
        }
        //removing the account input
        const fieldform1 = document.getElementById('accountHolderDiv');
        const accountNameInput = document.getElementById('accountName');
        const accountNumberInput = document.getElementById('accountName');
        if (accountNameInput && accountNumberInput) {
            fieldform1.removeChild(document.getElementById('accountName')) ;
            fieldform1.removeChild(document.getElementById('accountNUmber')) ;
            console.log('removed the accounts inputs');
        }


    }
}


// ============================ NAVIGATION SELECTION ACTIVENESS ==================================
function activeLink() {
    list.forEach((item) => {
        item.classList.remove("active");
        this.classList.add("active");

    })
}

list.forEach(item => {
    item.addEventListener("click",activeLink)

})

// ============================ TAB ACTIVATION ==================================
const openTab = (tabName) => {
    let tab = document.getElementsByClassName("tab__content-item");
    for (let i = 0; i < tab.length; i++) {
        tab[i].style.display = "none";
        // console.log('tabs');
    }
    document.getElementById(tabName).style.display = "block";
}


const openRegTab = (event,tabName1) => {
    let tab = document.getElementsByClassName("tab__content-item");
    for (let i = 0; i < tab.length; i++) {
        tab[i].style.display = "none";
        console.log('tabs');
    }
    document.getElementById(tabName1).style.display = "block";
}

const openBTab = (event,tabName2) => {
    let tab = document.getElementsByClassName("tab__content-item");
    for (let i = 0; i < tab.length; i++) {
        tab[i].style.display = "none";
    }
    document.getElementById(tabName2).style.display = "block";

}

//REPORT TAB
const openReportTab = (event3,tabName3) =>{
    let tab = document.getElementsByClassName("tab__content-item");
    for (let i = 0; i < tab.length; i++) {
        tab[i].style.display = "none";
    }
    document.getElementById(tabName3).style.display = "block";
}

for (let f = 0; f < dropdown.length; f++) {
    dropdown[f].addEventListener("click",function unkown(){
        arrorRight[0].classList.toggle('rotated');
        if (dropdownContent[0].style.display === "block") {
            dropdownContent[0].style.display = "none"
        }else{
            dropdownContent[0].style.display = "block";
        }
    })
}

//REPORT TAB
for (let K = 0; K < dropdown.length; K++) {
    dropdown2[K].addEventListener("click",function unkown(){
        arrorRight2[0].classList.toggle('rotated');
        if (dropdownContent2[0].style.display === "block") {
            dropdownContent2[0].style.display = "none"
        }else{
            dropdownContent2[0].style.display = "block";
        }
    })
}

// ============================ MODAL ACTIVENESS ==================================
// ADDING EVENLISTENTER TO ALL RECOMMEND BUTTON ON THE ASSEMENT TAB;

for (let i = 0; i < reButton.length; i++) {

    reButton[i].addEventListener("click",function modal(event){
        const borrowerID = event.target.id;
        const fieldform = document.getElementById('fieldForm');

        const hiddenInput = document.createElement("input");
        hiddenInput.setAttribute('type','hidden');
        hiddenInput.value = borrowerID;
        hiddenInput.setAttribute('id', 'borrowerID');
        hiddenInput.setAttribute('name', 'borrowerID');
        fieldform.appendChild(hiddenInput);

        modalContainer[0].classList.add("appear");

    })
}

for (let j = 0; j < changeButtons.length; j++) {
    changeButtons[j].addEventListener("click",function openModal(event) {
        alert("form the open model container")
        const borrowerID = event.target.id;
        const changeForm = document.getElementById('changeAmount');
        const hiddenInput = document.createElement("input");
        hiddenInput.setAttribute('type','hidden');
        hiddenInput.value = borrowerID;
        hiddenInput.setAttribute('id', 'borrowerID');
        hiddenInput.setAttribute('name', 'borrowerID');
        changeForm.appendChild(hiddenInput);

        modalContainer1[0].classList.add("appear");
    })
}


closeButton.addEventListener("click",(event) => {
    const fieldform = document.getElementById('fieldForm');
    borrowerInput = document.getElementById('borrowerID');
    fieldform.removeChild(borrowerInput) ;
    modalContainer[0].classList.remove("appear");
})

closeButton1.addEventListener("click",() => {
    const changeForm = document.getElementById('changeAmountadmin');
    borrowerInput = document.getElementById('borrowerIDadmin');
    changeForm.removeChild(borrowerInput) ;
    modalContainer1[0].classList.remove("appear");
})

closeButton2.addEventListener("click",() => {
    modalContainer2[0].classList.remove("appear");
})



// ============================ FORM VALIDATIONS ==================================
// Show input error message
function showError(message) {
    const formControl = document.getElementsByClassName("form-control");
    formControl.className = 'form-control error';
    const small = document.getElementById("bErrorMessage");
    small.style.visibility = "visible;"
    small.innerText = message;
  }

function generalShowError(message) {
    const errorContainer = document.getElementById("formErrorContainer");
    const errormessage = document.getElementById("formMessage");
    errorContainer.style.visibility = "visible";
    errormessage.innerText = message;

    setTimeout(() => {
        errorContainer.style.visibility = 'hidden';
        errormessage.innerText = "";

    }, 4000);
}
function generalShowSuccessMessage(message) {
    const successContainer = document.getElementById("formSuccessContainer");
    const successMessage = document.getElementById("formMessageSuccess");
    successContainer.style.visibility = "visible";
    successMessage.innerText = message;

    setTimeout(() => {
        // document.getElementById("borrowerFormResponse").innerHTML = "";
        successContainer.style.visibility = 'hidden';
        successMessage.innerText = "";

    }, 4000);
}

function showSuccess(message) {
    const small = document.getElementById("borrowerFormResponse");
    small.style.visibility = "visible;"
    small.innerText = message;
}


function submitBorrowerForm() {
    let borrowerForm = document.getElementById('borrower_Form');
    let formElement = document.getElementsByClassName("borrower_data");

    //getting the value momoids


    document.getElementsByClassName('spinner__container')[0].style.display = 'flex';
    let formData = new FormData();

    for (let i = 0; i < formElement.length; i++) {
        formData.append(formElement[i].name, formElement[i].value);

        if (formElement[i].value === "") {
            showError("Sorry All input field must be filled correctly");
            document.getElementById("bErrorMessage").style.visibility = 'visible';
            setTimeout(() => {document.getElementById("bErrorMessage").style.visibility = 'hidden';}, 2000);
            document.getElementsByClassName('spinner__container')[0].style.display = 'none';
            return;
        }

    }

    const disbursment_mode = document.getElementById('disbursementMode');

    if (disbursment_mode.options[disbursment_mode.selectedIndex].value == "momo") {
        let momo1 = document.getElementById('momoNumber').value;
        let momo2 = document.getElementById('momoNumber2').value;

        if (momo1 !== momo2) {
            generalShowError("sorry Momo numbers do not match");
            document.getElementsByClassName('spinner__container')[0].style.display = 'none';
            return;
        }
    }



    document.getElementById("submitBorrower").disabled = true;

    let ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open("POST", '../handlers/borrowerHandler.php');
    ajaxRequest.send(formData);
    ajaxRequest.onreadystatechange = function () {
        if (ajaxRequest.readyState == 4 && ajaxRequest.status == 200) {
            document.getElementById("submitBorrower").disabled = false;
            borrowerForm.reset();
            // checking the type of response text
            if (ajaxRequest.responseText === "BLACKLISTED") {
                generalShowError("Sorry this client cant be registered")
            }else{
                showSuccess(ajaxRequest.responseText);
                document.getElementById("borrowerFormResponse").style.visibility = 'visible';
            }


            //removing the populate disbusement mode for
            document.getElementsByClassName('spinner__container')[0].style.display = 'none';
            //removing the momo input;
            const fieldform = document.getElementById('momoDiv');
            const momoInput = document.getElementById('momoNumber');
            if (momoInput) {
                fieldform.removeChild(document.getElementById('momoNumber'));
                fieldform.removeChild(document.getElementById('momoNumber2'));
            }
            //removing the account input
            const fieldform1 = document.getElementById('accountHolderDiv');
            const accountNameInput = document.getElementById('accountName');
            const accountNumberInput = document.getElementById('accountName');
            if (accountNameInput && accountNumberInput) {
                fieldform1.removeChild(document.getElementById('accountName')) ;
                fieldform1.removeChild(document.getElementById('accountNUmber')) ;
            }

            document.getElementById("accountHolderDiv").style.display = "none";
            document.getElementById("momoDiv").style.display = "none";
            //removing the populate disbusement mode for ------------ END


            // calling all borrwes before the user get there
            loadAllBorrowers();

            //setting the form response message to nothing;
            setTimeout(() => {
                // document.getElementById("borrowerFormResponse").innerHTML = "";
                document.getElementById("borrowerFormResponse").style.visibility = 'hidden';

            }, 4000);
        }else if (ajaxRequest.readyState !== 4 && ajaxRequest.status !== 200) {
            setTimeout(() => {showError(ajaxRequest.responseText); }, 4000);
        }
    }
}


function submitExpense() {
  let expenseForm = document.getElementById('expenseForm');
  let expenseFormData = document.getElementsByClassName('expenseFormData');
  let formdataExpense = new FormData();


  for (var i = 0; i < expenseFormData.length; i++) {
    formdataExpense.append(expenseFormData[i].name, expenseFormData[i].value);

    if (expenseFormData[i].value === "") {
      generalShowError(`${expenseFormData[i].name} is empty please enter`);
      setTimeout(() => {document.getElementById("formErrorContainer").style.visibility = 'hidden';}, 2000);
      return ;
    }
  }

  document.getElementById("expenseSubtractButton").disabled = true;

  //STARTING WITH THE AJAX REQUEST;
  let ajaxRequest = new XMLHttpRequest();
  ajaxRequest.open("POST", '../handlers/expenseHandler.php');
  ajaxRequest.send(formdataExpense);
  document.getElementsByClassName('spinner__container')[0].style.display = 'flex';

  ajaxRequest.onreadystatechange = function () {
      if (ajaxRequest.readyState == 4 && ajaxRequest.status == 200) {
        document.getElementsByClassName('spinner__container')[0].style.display = 'none';
        document.getElementById("expenseSubtractButton").disabled = false;
        expenseForm.reset();
        generalShowSuccessMessage("expense  added successfully");
        //LOAD THE TABLE FOR ALL EXPENSES;
        loadAllExpenses()
      }else if (ajaxRequest.readyState !== 4 && ajaxRequest.status !== 200) {
        setTimeout(() => {showError(ajaxRequest.responseText); }, 4000);
      }


    }
}

function populateExpenseTable(jsonData) {
  if (expenseTable.firstChild) {
      while (expenseTable.firstChild) {
          expenseTable.removeChild(expenseTable.firstChild);
      }
  }

  jsonData.forEach(expenseData => {
      let tableData = `<tr>
          <td>${expenseData.expenseDescription}</td>
          <td>${expenseData.expenseDate}</td>
          <td>${expenseData.expenseAmount}</td>
      </tr>
      `;

      let newRow = expenseTable.insertRow(expenseTable.rows.length);
      // console.log(borrowerTable.firstChild);
      newRow.innerHTML = tableData;
      // newCell.appendChild()

  });


}

function loadAllExpenses() {
      let ajaxRequest = new XMLHttpRequest();
      const method = "GET";
      const url = "../requests/expenseRequest.php?getAllExpense=true";

      ajaxRequest.open(method,url);

      ajaxRequest.onreadystatechange = function () {
          if ( ajaxRequest.status == 200) {
              if (this.readyState == 4) {
                  if (ajaxRequest.responseText == "false") {
                      expenseTable.innerHTML = "<h1>sorry no current data was found</h1>";
                  }else{
                      const responesData = JSON.parse(ajaxRequest.responseText);
                      populateExpenseTable(responesData);

                  }
              }
          }
      }
      ajaxRequest.send();
}

function submitEmployeeForm() {

    let employeeform = document.getElementById('employeeForm');
    let formElement = document.getElementsByClassName("employeeFormData");
    let formData = new FormData();


    for (let i = 0; i < formElement.length; i++) {
        formData.append(formElement[i].name, formElement[i].value);

        if (formElement[i].value === "") {
            generalShowError("Please all input must be filled");

            setTimeout(() => {document.getElementById("formErrorContainer").style.visibility = 'hidden';}, 2000);
            return;
        }else if (formElement['emp_password'].value !==formElement['emp_confirmpassword'].value ) {
            generalShowError("password does not match");

            setTimeout(() => {document.getElementById("formErrorContainer").style.visibility = 'hidden';}, 2000);
            return;
        }

    }

    document.getElementById("addAnEmployee").disabled = true;

    let ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open("POST", '../handlers/employeeHandler.php');
    ajaxRequest.send(formData);
    document.getElementsByClassName('spinner__container')[0].style.display = 'flex';
    ajaxRequest.onreadystatechange = function () {
        if (ajaxRequest.readyState == 4 && ajaxRequest.status == 200) {
            document.getElementsByClassName('spinner__container')[0].style.display = 'none';
            document.getElementById("addAnEmployee").disabled = false;
            employeeform.reset();
            showSuccess(ajaxRequest.responseText);
            document.getElementById("borrowerFormResponse").style.visibility = 'visible';
            // calling all borrwes before the user get there
            generalShowSuccessMessage("Employee added successfully");
            loadAllEmployees();
            openTab('employees');

            //setting the form response message to nothing;

        }else if (ajaxRequest.readyState !== 4 && ajaxRequest.status !== 200) {
            setTimeout(() => {showError(ajaxRequest.responseText); }, 4000);
        }
    }
}




function loadAllBorrowers() {
    let ajaxRequest = new XMLHttpRequest();
    const method = "GET";
    const url = "../requests/borrowerRequest.php";

    ajaxRequest.open(method,url);

    ajaxRequest.onreadystatechange = function () {
        if ( ajaxRequest.status == 200) {
            if (this.readyState == 4) {
                if (ajaxRequest.responseText == "false") {
                    borrowerTable.innerHTML = "<h1>sorry no current data was found</h1>";
                }else{
                    const responesData = JSON.parse(ajaxRequest.responseText);
                    populateBorrowerTable(responesData);

                }
            }
        }
    }
    ajaxRequest.send();
}

function loadAllEmployees() {
    let ajaxRequest = new XMLHttpRequest();
    const method = "GET";
    const url = "../handlers/employeeHandler.php?getAllEmpleyees";

    ajaxRequest.open(method,url);
    ajaxRequest.onreadystatechange = function () {
        if ( ajaxRequest.status == 200) {
            if (this.readyState == 4) {
                if (ajaxRequest.responseText == "NOEMPLOYEEWASFOUND") {
                    employeeTable.innerHTML = "<h1>You have no employees</h1>";
                }else{
                    const responesData = JSON.parse(ajaxRequest.responseText);
                    populateEmployeeTable(responesData);


                }
            }
        }
    }
    ajaxRequest.send();
}

function populateEmployeeTable(data) {
    if (employeeTable.firstChild) {

        while (employeeTable.firstChild) {
            employeeTable.removeChild(employeeTable.firstChild);
        }
    }

    data.forEach(employee => {
        let tableData = `<tr class='customer__table-body-row'>
            <td><img src='../img/img_avatar2.png' alt='User image' style='width: 30px; border-radius: 100px;'></td>
            <td>${employee.fullname}</td>
            <td>${employee.number}</td>
            <td>${employee.location}</td>
            <td>${employee.department}</td>

            <td data-label='status'>
                <a href='../pages/clients/profile.php?employeeDisplayId=${employee.id}' class='status ontime link_clear recommendButtom' onclick="displayEmployeModal(${employee.id});return false;">View Infor</a>
                <a href='#' onclick="deleteSpecifiEmployee(${employee.id});return false;" class='status danger link_clear recommendButtom'>Delete</a>
            </td>
        </tr>
        `;

        let newRow = employeeTable.insertRow(employeeTable.rows.length);
        // console.log(borrowerTable.firstChild);
        newRow.innerHTML = tableData;
        // newCell.appendChild()

    });
}



function populateBorrowerTable(jsonData) {
    // const borrowerTable = document.getElementById("borrowerTable").getElementsByTagName('tbody')[0];
    //clearing out existing table data
    if (borrowerTable.firstChild) {

        while (borrowerTable.firstChild) {
            borrowerTable.removeChild(borrowerTable.firstChild);
        }
    }

    //populate the table
    jsonData.forEach(borrower => {
        let tableData = `<tr class='customer__table-body-row'>
            <td>${borrower.b_fullname}</td>
            <td>${borrower.b_businesstype}</td>
            <td>${borrower.b_businessLocation}</td>
            <td>${borrower.b_contact}</td>
            <td>${borrower.disbursmentMode}</td>
            <td>${borrower.amountRequested}</td>
            <td data-label='status'>
                <a href='#' class='status danger link_clear' onclick='deleteBorrowre(${borrower.b_id})'>Reject</a>
                <a href='#' class='status ontime link_clear recommendButtom' id='${borrower.b_id}' onclick='recommendCLient()'>Recommend</a>
            </td>
        </tr>
        `;

        let newRow = borrowerTable.insertRow(borrowerTable.rows.length);
        // console.log(borrowerTable.firstChild);
        newRow.innerHTML = tableData;
        // newCell.appendChild()

    });
}

function recommendamountbyAdmin(e) {
    // code to submit the recommend amount by admin;
    let inputField = document.getElementById('changeAmountadmin').querySelectorAll('input');
    let formData = new FormData();
    for (let i = 0; i < inputField.length; i++) {
        formData.append(inputField[i].name, inputField[i].value);
        if (inputField[i].value === "") {
            alert("please recommend an amount");
            return
        }
    }
    let ajaxRequest = new XMLHttpRequest();
    const method = "POST";
    const url = "../handlers/formHandler.php";
    document.getElementsByClassName('spinner__container')[0].style.display = 'flex';
    ajaxRequest.open(method,url);
    ajaxRequest.send(formData);
    ajaxRequest.onload = () => {
    try {
        let result = ajaxRequest.responseText;
        if (result == "true") {
            alert(result);
            const fieldform = document.getElementById('changeAmountadmin');
            borrowerInput = document.getElementById('borrowerIDadmin');
            fieldform.removeChild(borrowerInput) ;
            fieldform.reset();
            modalContainer1[0].classList.remove("appear");
            document.getElementsByClassName('spinner__container')[0].style.display = 'none';
            loadAllBorrowers();
            getAllApprovedCLients();
            getAllDisbursementList();
        }
    } catch (e) {
        alert(e);
    }
    }
}


function adminApprove(id) {
    let ajaxRequest = new XMLHttpRequest();
    const method = "GET";
    const url = `../requests/ajaxRequest.php?adminApproveID=${id}`;
    document.getElementsByClassName('spinner__container')[0].style.display = 'flex';

    ajaxRequest.open(method,url);
    ajaxRequest.onload = () =>{
        try {
            const deleteSucces = ajaxRequest.responseText;
            if (deleteSucces) {
                //LOAD ALL APPROVED CLIENTS;
                getAllApprovedCLients();
                getAllDisbursementList();
                document.getElementsByClassName('spinner__container')[0].style.display = 'none';
            }

        } catch (error) {
            console.log(error);
        }
    }
    ajaxRequest.send();
}

// THIS FUNCTION WILL ADD THE USER TO THE FIELD ASSESSMENT TABLE ON THE PHP PAGE WHEN RECOMMENTCLINET ON THE PHP PAGE IS CLICKED
function recommendClient(e) {
    let RecommendFOrm = document.getElementById('fieldForm');
    let inputField = document.getElementById('fieldForm').querySelectorAll('input');
    let formData = new FormData();

    for (let z = 0; z < inputField.length; z++) {
        formData.append(inputField[z].name, inputField[z].value);
        if (inputField[z].value === "") {
            alert("sorry all input must be fild");
            return
        }
    }
    let ajaxRequest = new XMLHttpRequest();
    const method = "POST";
    const url = "../requests/ajaxRequest.php";
    const async = true;
    document.getElementsByClassName('spinner__container')[0].style.display = 'flex';
    ajaxRequest.open(method,url,async);

    ajaxRequest.send(formData);
    ajaxRequest.onload = () => {
        try {
            let result = ajaxRequest.responseText;
            if (result == "true") {
                const fieldform = document.getElementById('fieldForm');
                borrowerInput = document.getElementById('borrowerID');
                fieldform.removeChild(borrowerInput) ;
                fieldform.reset();
                modalContainer[0].classList.remove("appear");
                document.getElementsByClassName('spinner__container')[0].style.display = 'none';
                loadAllBorrowers();
                getAllApprovedCLients();
            }
        } catch (e) {
            alert(e);
        }
    }
}

function deleteBorrowre(id) {
    let ajaxRequest = new XMLHttpRequest();
    const method = "GET";
    const url = `../requests/ajaxRequest.php?borrowerID=${id}`;
    const async = true;
    document.getElementsByClassName('spinner__container')[0].style.display = 'flex';

    ajaxRequest.open(method,url,async);
    ajaxRequest.onload = () =>{
        try {
            const deleteSucces = ajaxRequest.responseText;
            if (deleteSucces) {
                loadAllBorrowers();
                document.getElementsByClassName('spinner__container')[0].style.display = 'none';
            }

        } catch (error) {
            console.log(error);
        }
    }
    ajaxRequest.send();
}

function deleteApprovedCLient(borrowerID,clientId) {
    document.getElementsByClassName('spinner__container')[0].style.display = 'flex';
    let ajaxRequest = new XMLHttpRequest();
    const method = "GET";
    const url = `../requests/ajaxRequest.php?adminDeleteClient=${clientId}&CleintDelID=${borrowerID}`;
    ajaxRequest.open(method,url);
    ajaxRequest.onload = () =>{
        try {
            const deleteSucces = ajaxRequest.responseText;
            if (deleteSucces) {
                getAllApprovedCLients();
                getAllDisbursementList();
                document.getElementsByClassName('spinner__container')[0].style.display = 'none';
            }else{
                console.log('error deleting borrower',deleteSucces);
            }

        } catch (error) {
            console.log(error);
        }
    }
    ajaxRequest.send();
}


function deleteChronicUser(chronicID) {
    let ajaxRequest = new XMLHttpRequest();
    const method = "GET";
    const url = `../requests/chronicRequest.php?deleteChronicUSer=${chronicID}`;
    document.getElementsByClassName('spinner__container')[0].style.display = 'flex';
    ajaxRequest.open(method,url);
    ajaxRequest.onload = () =>{
        try {
            const deleteSucces = ajaxRequest.responseText;
            if (deleteSucces == "DELETESUCCESS") {
                console.log(deleteSucces);
                document.getElementsByClassName('spinner__container')[0].style.display = 'none';
                getChronicCLients();
            }
        } catch (error) {
            console.log(error);
        }
    }
    ajaxRequest.send();
}

function deleteBlacklistUser(blacklistID) {
    document.getElementsByClassName('spinner__container')[0].style.display = 'flex';
    fetch(`../requests/chronicRequest.php?blacklistDeleteID=${blacklistID}`,{
        method: "GET",
    }).then(res => res.text())
    .then(jsondata => {
        document.getElementsByClassName('spinner__container')[0].style.display = 'none';
        generalShowSuccessMessage(jsondata);
        getBlacklistedClients();
    });
}

function deleteSpecifiEmployee(employeeID) {
    document.getElementsByClassName('spinner__container')[0].style.display = 'flex';
    fetch(`../handlers/employeeHandler.php?delSpecificEmployee=${employeeID}`,{
        method: "GET",
    }).then(res => res.text())
    .then(jsondata => {
        document.getElementsByClassName('spinner__container')[0].style.display = 'none';
        generalShowSuccessMessage(jsondata);
        loadAllEmployees(jsondata);

    });
}

function getAllApprovedCLients() {
    let ajaxRequest = new XMLHttpRequest();
    const method = "GET";
    const url = "../requests/ajaxRequest.php?allAprovedCLient=true";

    ajaxRequest.open(method,url);

    ajaxRequest.onreadystatechange = function () {
        if ( ajaxRequest.status == 200) {
            if (this.readyState == 4) {
                if (ajaxRequest.responseText == "NOAPPROVEDCLIENTS") {
                    accessmentTable.innerHTML = "<h1>sorry no accessed clients</h1>";
                }else{

                    const responesData = JSON.parse(ajaxRequest.responseText);

                    populateApproveClient(responesData)
                }

                // alert(ajaxRequest.responseText);
            }
        }
    }

    ajaxRequest.send();
}


function populateApproveClient(data) {
    if (accessmentTable.firstChild) {
        while (accessmentTable.firstChild) {
            accessmentTable.removeChild(accessmentTable.firstChild);
        }
    }

    //populate the table
    data.forEach(aprrovedCleint => {
        const borrowerID = aprrovedCleint.b_id;
        let tableData = `<tr class='customer__table-body-row'>
            <td>${aprrovedCleint.b_fullname}</td>
            <td>${aprrovedCleint.b_businesstype}</td>
            <td>${aprrovedCleint.b_businessLocation}</td>
            <td>${aprrovedCleint.b_contact}</td>
            <td>${aprrovedCleint.disbursmentMode}</td>
            <td>${aprrovedCleint.amountRequested}</td>
            <td>${aprrovedCleint.amountRecommend}</td>
            <td data-label='action_content'>
                <a href='#' onclick='adminApprove(${borrowerID})' class='status ontime link_clear' id=''>Confirm</a>
                <a href='#' class='status read link_clear changeButton' id='${borrowerID}' onclick='displayAdminRecommendAccount()';>Update</a>
                <a href='#' class='status danger link_clear changeButton' onclick='deleteApprovedCLient(${borrowerID})' id='${borrowerID}'><ion-icon name="trash-outline"></ion-icon></a>
            </td>
        </tr>
        `;

        let newRow = accessmentTable.insertRow(accessmentTable.rows.length);
        // console.log(borrowerTable.firstChild);
        newRow.innerHTML = tableData;
    })


}

function getAllRegisteredClient() {
    let ajaxRequest = new XMLHttpRequest();
    const method = "GET";
    const url = "../requests/ajaxRequest.php?getAllCurrentClient=true";
    ajaxRequest.open(method,url);

    ajaxRequest.onreadystatechange = function () {
        if ( ajaxRequest.status == 200) {
            if (this.readyState == 4) {
                if (ajaxRequest.responseText == false) {
                    customerTable.innerHTML = "<h1>sorry no client found</h1>";
                }else{
                    const responesData = JSON.parse(ajaxRequest.responseText);
                    populateRegisteredClients(responesData)
                }
            }
        }
    }

    ajaxRequest.send();
}

function getAllDisbursementList() {
    let ajaxRequest = new XMLHttpRequest();
    const method = "GET";
    const url = "../requests/ajaxRequest.php?disbursementlist=true";
    ajaxRequest.open(method,url);

    ajaxRequest.onreadystatechange = function () {
        if ( ajaxRequest.status == 200) {
            if (this.readyState == 4) {
                if (ajaxRequest.responseText == "NODISPURSEMENTLIST") {
                    disbursementTable.innerHTML = "<h1>Sorry no disbursement list now</h1>";
                }else{

                    const responesData = JSON.parse(ajaxRequest.responseText);
                    // console.log(ajaxRequest.responseText);
                    populateDisbursementTable(responesData)
                }

                // alert(ajaxRequest.responseText);
            }
        }
    }
    ajaxRequest.send();
}

function populateDisbursementTable(data) {
    let disbursmentInfor = "Physical Cash";
    if (disbursementTable.firstChild) {
        while (disbursementTable.firstChild) {
            disbursementTable.removeChild(disbursementTable.firstChild);
        }
    }

    data.forEach(disbursementlist => {
        const borrowerID = disbursementlist.b_id;
        const originalCLientID = disbursementlist.client_id;
        if (disbursementlist.disbursmentMode == 'momo') {
            disbursmentInfor = disbursementlist.momoNumber;
        }else if(disbursementlist.disbursmentMode == 'account'){
            disbursmentInfor = disbursementlist.accoutName + " " + disbursementlist.accountNumber;
        }

        let tableData = `<tr class='customer__table-body-row'>
            <td>${disbursementlist.b_fullname}</td>
            <td>${disbursementlist.b_businessLocation}</td>
            <td>${disbursementlist.b_contact}</td>
            <td>${disbursementlist.amountRecommend}</td>
            <td>${disbursementlist.disbursmentMode}</td>
            <td>${disbursmentInfor}</td>

            <td data-label='action_content'>
                <a href='#' class='status ontime link_clear' onclick='confirmPayment(${originalCLientID},${borrowerID},${disbursementlist.amountRecommend});return false;'>Confirm payment</a>
                <a href='#' class='status danger link_clear changeButton' onclick='deleteApprovedCLient(${originalCLientID},${borrowerID});return false;' id='${borrowerID}'>Delete</a>
            </td>
        </tr>
        `;

        let newRow = disbursementTable.insertRow(disbursementTable.rows.length);
        newRow.innerHTML = tableData;
    });
}

function populateRegisteredClients(data) {
    if (customerTable.firstChild) {
        while (customerTable.firstChild) {
            customerTable.removeChild(customerTable.firstChild);
        }
    }

    data.forEach(registerCLients => {

        let indicate;
        if (registerCLients.status == "on track") {
            indicate = 'ontime';
        }else{
            indicate = 'lateonpayment';
        }
        let tableData = `<tr>
            <td><img src='../img/img_avatar2.png' alt='User image' style='width: 30px; border-radius: 100px;'></td>
            <td>${registerCLients.fullname}</td>
            <td>${registerCLients.occupation}</td>
            <td>GH&#162;${registerCLients.loanAmount}</td>
            <td>GH&#162;${registerCLients.pendingBalance}</td>
            <td>${registerCLients.telephone}</td>
            <td>${registerCLients.nextPayment}</td>
            <td>${registerCLients.track}</td>
            <td><span class='ref'>${registerCLients.reference_code}</span></td>
            <td><span class="${indicate}">${registerCLients.status}</span></td>


            <td data-label='status'>
                <span class='status update'><a href='../pages/clients/profile.php?cliendID=${registerCLients.client_id}' class='link_clear'>Update</a></span>
            </td>
        </tr>
        `;
        let newRow = customerTable.insertRow(customerTable.rows.length);
        newRow.innerHTML = tableData;
    });


}

function confirmPayment(cliendID,borrowerID,approvedamout) {
    let ajaxRequest = new XMLHttpRequest();
    const method = "GET";
    const url = `../requests/ajaxRequest.php?paymentID=${cliendID}&IDborrower=${borrowerID}&loanAmount=${approvedamout}`;
    document.getElementsByClassName('spinner__container')[0].style.display = 'flex';
    ajaxRequest.open(method,url);

    ajaxRequest.onreadystatechange = function () {
        if ( ajaxRequest.status == 200) {
            if (this.readyState == 4) {
                if (ajaxRequest.responseText == "INSERTIONANDDELETEIONFAILED") {
                    document.getElementsByClassName('spinner__container')[0].style.display = 'none';
                    console.log(ajaxRequest.responseText);
                    // alert();
                }else{
                    document.getElementsByClassName('spinner__container')[0].style.display = 'none';
                    getAllDisbursementList();
                    getAllRegisteredClient();
                }
            }
        }
    }

    ajaxRequest.send();
}

function getChronicCLients() {
    let ajaxRequest = new XMLHttpRequest();
    const method = "GET";
    const url = "../requests/chronicRequest.php?getChronicClients";
    ajaxRequest.open(method,url);

    ajaxRequest.onreadystatechange = function () {
        if ( ajaxRequest.status == 200) {
            if (this.readyState == 4) {
                if (ajaxRequest.responseText == false) {
                    chronicTable.innerHTML= "<h1>SORRY NO CHRONIC CLIENTS FOR NOW CHECK BACK LATER</h1>";
                }else{
                    const responesData = JSON.parse(ajaxRequest.responseText);
                    populateChronicTable(responesData);

                }
            }
        }
    }

    ajaxRequest.send();
}

function getBlacklistedClients() {
    fetch("../requests/chronicRequest.php?getAllBlacklistedClients",{
        method: "GET",
    }).then(res => res.json())
    .then(jsondata => {
        if (jsondata) {
            populateBlacktable(jsondata);
        }else{
            blacklisttable.innerHTML = "<h1>SORRY NO BLACKLISTED CLIENTS</h1>";
        }
    });
}

function populateBlacktable(jsondata) {
    if (blacklisttable.firstChild) {
        while (blacklisttable.firstChild) {
            blacklisttable.removeChild(blacklisttable.firstChild);
        }
    }

    jsondata.forEach(blacklistData => {
        let tableData = `<tr class='customer__table-body-row'>
            <td><img src='../img/img_avatar2.png' alt='User image' style='width: 30px; border-radius: 100px;'></td>
            <td>${blacklistData.fullname}</td>
            <td>${blacklistData.telephone}</td>
            <td>${blacklistData.location}</td>
            <td>${blacklistData.votersID}</td>
            <td>${blacklistData.date_added}</td>



            <td data-label='status'>
                <span class='status danger'><a href='#' class='link_clear' onclick="deleteBlacklistUser(${blacklistData.client_id}); return false;">Delete</a></span>
            </td>
        </tr>
        `;
        let newRow = blacklisttable.insertRow(blacklisttable.rows.length);
        newRow.innerHTML = tableData;
    })
}

function populateChronicTable(data) {
    if (chronicTable.firstChild) {
        while (chronicTable.firstChild) {
            chronicTable.removeChild(chronicTable.firstChild);
        }
    }

    data.forEach(clientdata => {
        let tableData = `<tr class='customer__table-body-row'>
            <td><img src='../img/img_avatar2.png' alt='User image' style='width: 30px; border-radius: 100px;'></td>
            <td>${clientdata.fullname}</td>
            <td>GH&#162;${clientdata.loanAmount}</td>
            <td>GH&#162;${clientdata.pendingBalance}</td>
            <td>${clientdata.telephone}</td>
            <td>${clientdata.completePayment}</td>
            <td><span class='defaulted'>Chronic</span></td>


            <td data-label='status'>
                <span class='status ontime'><a href='../pages/clients/profile.php?cliendID=${clientdata.client_id}' class='link_clear'>Infor</a></span>
                <span class='status read'><a href='#' class='link_clear blacklistButtons' onclick='addToBlacklist(${clientdata.client_id},${clientdata.votersID})'>Block</a></span>
                <span class='status danger'><a href='#' class='link_clear' onclick="deleteChronicUser(${clientdata.client_id}); return false;"><ion-icon name="trash-outline" role="img" class="md hydrated" aria-label="trash outline"></ion-icon></a></span>
            </td>
        </tr>
        `;
        let newRow = chronicTable.insertRow(chronicTable.rows.length);
        newRow.innerHTML = tableData;
    });

}


function addToBlacklist(cid,clientsvID) {
    document.getElementsByClassName('spinner__container')[0].style.display = 'flex';
    fetch("../requests/chronicRequest.php",{
        method: "POST",
        body: new URLSearchParams(`blacklistID=${cid}&clientsVotersID=${clientsvID}`)
    }).then(res => res.text())
    .then(jsondata => {
        document.getElementsByClassName('spinner__container')[0].style.display = 'none';
        generalShowSuccessMessage(jsondata);
        getBlacklistedClients();
    });
}

function getLiveUserSearch(data) {
    if (searchInputFiedld.value == null || searchInputFiedld.value == "") {
        searchResultContainer.innerHTML = "";
    }else{
        if (data) {
            if (!isNaN(data)) {
                if (data.length == 4) {
                    fetchSearchData(data);
                }
            }else{
                fetchSearchData(data);
            }
        }else{
            searchResultContainer.innerHTML = "";
        }
    }


    // searchResultContainer.innerHTML = "";
}

function fetchSearchData(searchdata) {


    fetch("../handlers/search.php",{
        method: "POST",
        body: new URLSearchParams('searchUser='+searchdata)
    })
    .then(res => res.json())
    .then(jsonData => {

        if (jsonData) {
            jsonData.forEach(clientData => {
                let stringTemplate = `<a href="../pages/clients/profile.php?cliendID=${clientData.client_id}" class="link_clear searchResultLink">
                <div class="searchImageResult" style="margin-right: 20px;">
                    <img src="../img/img_avatar.png" alt="user image" style="border-radius: 100px; width: 30px;">
                </div>
                <div class="searchTextResult">
                    <div class="clientInforSeacrch">
                        <span style="font-weight: bold;font-size: 1.5rem;">${clientData.fullname}</span>
                    </div>
                    <div class="clientGurantorInforSearch">
                        <span>${clientData.telephone}</span>
                    </div>
                </div>
            </a>`;
            searchResultContainer.innerHTML = stringTemplate;
            });
        }else{
            searchResultContainer.innerHTML = "";
        }
    })
    .catch( e => console.log(e));
    console.log(searchResultContainer);
}




// ============================ END ASYNC FUNCTION ==================================
// ACTIVATING THE RECOMMEND MODAL WHEN THE RECOMMEND BUTTON IS PRESSED;
function recommendCLient() {
    const borrowerID = event.target.id;
    const fieldform = document.getElementById('fieldForm');

    const hiddenInput = document.createElement("input");
    hiddenInput.setAttribute('type','hidden');
    hiddenInput.value = borrowerID;
    hiddenInput.setAttribute('id', 'borrowerID');
    hiddenInput.setAttribute('name', 'borrowerID');
    fieldform.appendChild(hiddenInput);

    modalContainer[0].classList.add("appear");
}
// ACTIVATING THE recommend MODAL for the ADMIN WHEN THE CHANGE BUTTON IS PRESSED;
function displayAdminRecommendAccount() {
        const borrowerID = event.target.id;
        const changeForm = document.getElementById('changeAmountadmin');
        const hiddenInput = document.createElement("input");
        hiddenInput.setAttribute('type','hidden');
        hiddenInput.value = borrowerID;
        hiddenInput.setAttribute('id', 'borrowerIDadmin');
        hiddenInput.setAttribute('name', 'borrowerID');
        changeForm.appendChild(hiddenInput);

        modalContainer1[0].classList.add("appear");
}
// DISPLAYING THE EMPLOYEE MODAL
function displayEmployeModal(employeeID) {
    const employ = employeeID;
    let formElement = document.getElementsByClassName("employeeFormFieldData");

    let ajaxRequest = new XMLHttpRequest();
    const method = "GET";
    const url = `../handlers/employeeHandler.php?getEmployeeByID=${employ}`;
    document.getElementsByClassName('spinner__container')[0].style.display = 'flex';

    ajaxRequest.open(method,url);
    ajaxRequest.onreadystatechange = function () {
        if ( ajaxRequest.status == 200) {
            if (this.readyState == 4) {
                if (ajaxRequest.responseText == "NOEMPLOYEEWASFOUND") {
                    employeeTable.innerHTML = "<h1>You have no employees</h1>";
                }else{
                    document.getElementsByClassName('spinner__container')[0].style.display = 'none';
                    const responesData = JSON.parse(ajaxRequest.responseText);
                    for (let z = 0; z < formElement.length; z++) {
                        if (formElement[z].name == 'employeeName') {
                            formElement[z].value = responesData[0]['fullname'];
                        }
                        if (formElement[z].name == 'employeenumber') {
                            formElement[z].value = responesData[0]['number'];
                        }
                        if (formElement[z].name == 'employeelocation') {
                            formElement[z].value = responesData[0]['location'];
                        }
                        if (formElement[z].name == 'employeeDepartement') {
                            formElement[z].value = responesData[0]['department'];
                        }
                        if (formElement[z].name == 'emplyeeid') {
                            formElement[z].value = responesData[0]['staffID'];
                        }
                        if (formElement[z].name == 'employeePassword') {
                            formElement[z].value = responesData[0]['password'];
                        }


                    }
                    console.log(responesData[0]);
                }
            }
        }
    }
    ajaxRequest.send();


    modalContainer2[0].classList.add("appear");
}

// ============================ INDIVIDUAL DATE DISPLAYING ==================================
let dt = new Date();
const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
document.getElementById("datetime").innerHTML = dt.toDateString();
document.getElementById("printDate").innerHTML = dt.toDateString();
document.getElementById("day").innerHTML = days[dt.getDay()];
