const list = document.querySelectorAll('.list');
let dropdown = document.getElementsByClassName('dropdown_button');
let dropdownContent = document.getElementsByClassName("dropdownContent");
let arrorRight = document.getElementsByClassName("arrowRight");

const toggleModel = document.getElementById("recommend");
const reButton = document.getElementsByClassName("recommendButtom");


const toggleModel1 = document.getElementById("recommend1");// delete after code below works;
const changeButtons = document.getElementsByClassName("changeButton");

const closeButton = document.getElementById("closeModal");
const closeButton1 = document.getElementById("closeModal1");

const modalContainer = document.getElementsByClassName("model__container");
const modalContainer1 = document.getElementsByClassName("model__container1");
const borrowerTable = document.getElementById("borrowerTable").getElementsByTagName('tbody')[0];
const accessmentTable = document.getElementById("accessmentTable").getElementsByTagName('tbody')[0];
const disbursementTable = document.getElementById("disbursementTable").getElementsByTagName('tbody')[0];
const customerTable = document.getElementById("allCustomerTable").getElementsByTagName('tbody')[0];
const chronicTable = document.getElementById("chronicTable").getElementsByTagName('tbody')[0];

// ============================  DISPLAY INPUT FIELD ==================================
function showInputField(nameSelect) {
    if (nameSelect.value == "momo") {
        
        const fieldform = document.getElementById('momoDiv');

        NodeList.prototype.forEach = Array.prototype.forEach
        let children = fieldform.childNodes;
        children.forEach(function(item){
            if (item.id == 'momoNumber') {
                fieldform.removeChild(document.getElementById('momoNumber')) ;
            }
        });
        
        const momoNumberinput = document.createElement("input");
        momoNumberinput.placeholder = "Enter Momo Number"
        momoNumberinput.setAttribute('type','number');
        momoNumberinput.setAttribute('id', 'momoNumber');
        momoNumberinput.setAttribute('name', 'momoNumber');
        momoNumberinput.className= "borrower_data";
        fieldform.appendChild(momoNumberinput);
        
        document.getElementById("momoDiv").style.display = "block";
        document.getElementById("accountHolderDiv").style.display = "none";

       
    }else if (nameSelect.value == "account") {

        const fieldform1 = document.getElementById('accountHolderDiv');

        NodeList.prototype.forEach = Array.prototype.forEach
        let children = fieldform1.childNodes;
        children.forEach(function(item){
            if (item.id == 'accountName') {
                fieldform1.removeChild(document.getElementById('accountName')) ;
                fieldform1.removeChild(document.getElementById('accountNUmber')) ;
            }
        });

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

// ============================ FORM VALIDATIONS ==================================
// Show input error message
function showError(message) {
    const formControl = document.getElementsByClassName("form-control");
    formControl.className = 'form-control error';
    const small = document.getElementById("bErrorMessage");
    small.style.visibility = "visible;"
    small.innerText = message;
  }

  function showSuccess(message) {
    const small = document.getElementById("borrowerFormResponse");
    small.style.visibility = "visible;"
    small.innerText = message;
  }


function submitBorrowerForm() {
    let borrowerForm = document.getElementById('borrower_Form');
    let formElement = document.getElementsByClassName("borrower_data");
    let formData = new FormData();

    for (let i = 0; i < formElement.length; i++) {
        formData.append(formElement[i].name, formElement[i].value);
        if (formElement[i].value === "") {
            showError("Sorry All input field must be filled");
            document.getElementById("bErrorMessage").style.visibility = 'visible';
            setTimeout(() => {document.getElementById("bErrorMessage").style.visibility = 'hidden';}, 2000);
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
            showSuccess(ajaxRequest.responseText);
            document.getElementById("borrowerFormResponse").style.visibility = 'visible';
            //removing the populate disbusement mode for
            const fieldform1 = document.getElementById('accountHolderDiv');
            NodeList.prototype.forEach = Array.prototype.forEach
            let children = fieldform1.childNodes;
            if (children) {
                children.forEach(function(item){
                    if (item.id == 'accountName') {
                        fieldform1.removeChild(document.getElementById('accountName')) ;
                        fieldform1.removeChild(document.getElementById('accountNUmber')) ;
                    }
                });
            }

            const fieldform = document.getElementById('momoDiv');
            let children1 = fieldform.childNodes;
            if (children1) {
                children1.forEach(function(item){
                    if (item.id == 'momoNumber') {
                        fieldform.removeChild(document.getElementById('momoNumber')) ;
                    }
                }); 
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

    ajaxRequest.open(method,url);
    ajaxRequest.onload = () =>{
        try {
            const deleteSucces = ajaxRequest.responseText;
            if (deleteSucces) {
                //LOAD ALL APPROVED CLIENTS;
                getAllApprovedCLients();
                getAllDisbursementList();
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

    ajaxRequest.open(method,url,async);
    ajaxRequest.onload = () =>{
        try {
            const deleteSucces = ajaxRequest.responseText;
            if (deleteSucces) {
                loadAllBorrowers();
            }
            
        } catch (error) {
            console.log(error);
        }
    }
    ajaxRequest.send();
}

function deleteApprovedCLient(clientId) {
    let ajaxRequest = new XMLHttpRequest(); 
    const method = "GET";
    const url = `../requests/ajaxRequest.php?adminDeleteClient=${clientId}`;
    ajaxRequest.open(method,url);
    ajaxRequest.onload = () =>{
        try {
            const deleteSucces = ajaxRequest.responseText;
            if (deleteSucces) {
                getAllApprovedCLients();
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
    ajaxRequest.open(method,url);
    ajaxRequest.onload = () =>{
        try {
            const deleteSucces = ajaxRequest.responseText;
            if (deleteSucces == "DELETESUCCESS") {
                console.log(deleteSucces);
                getChronicCLients();
            }
        } catch (error) {
            console.log(error);
        }
    }
    ajaxRequest.send();
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
                    console.log(ajaxRequest.responseText);
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
                <a href='#' onclick='adminApprove(${borrowerID})' class='status ontime link_clear' id=''>Aprrove</a>
                <a href='#' class='status danger link_clear changeButton' id='${borrowerID}' onclick='displayAdminRecommendAccount()';>Change</a>
                <a href='#' class='status danger link_clear changeButton' onclick='deleteApprovedCLient(${borrowerID})' id='${borrowerID}'>Delete</a>  
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
                <a href='#' class='status ontime link_clear' onclick='confirmPayment(${borrowerID},${disbursementlist.amountRecommend});return false;'>Confirm payment</a>
                <a href='#' class='status danger link_clear changeButton' onclick='deleteApprovedCLient(${borrowerID});return false;' id='${borrowerID}'>Delete</a>  
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
        let tableData = `<tr class='customer__table-body-row'>
            <td><img src='../img/img_avatar2.png' alt='User image' style='width: 30px; border-radius: 100px;'></td>
            <td>${registerCLients.fullname}</td>
            <td>2</td>
            <td>GH&#162;${registerCLients.loanAmount}</td>
            <td>GH&#162;${registerCLients.pendingBalance}</td>
            <td>${registerCLients.telephone}</td>
            <td>${registerCLients.nextPayment}</td>
            <td>${registerCLients.track}</td>
            <td><span class='ref'>${registerCLients.reference_code}</span></td>
            <td><span class='ontime'>${registerCLients.status}</span></td>
            
            
            <td data-label='status'>
                <span class='status update'><a href='./clients/profile.html?c_id={$clientid}' class='link_clear'>Update</a></span>
            </td>
        </tr>
        `;
        let newRow = customerTable.insertRow(customerTable.rows.length);
        newRow.innerHTML = tableData;
    });


}

function confirmPayment(cliendID,approvedamout) {
    let ajaxRequest = new XMLHttpRequest(); 
    const method = "GET";
    const url = `../requests/ajaxRequest.php?paymentID=${cliendID}&loanAmount=${approvedamout}`;
    ajaxRequest.open(method,url);
   
    ajaxRequest.onreadystatechange = function () {
        if ( ajaxRequest.status == 200) {
            if (this.readyState == 4) {
                if (ajaxRequest.responseText == "INSERTIONANDDELETEIONFAILED") {
                    console.log(ajaxRequest.responseText);
                    // alert();
                }else{  
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
                    chronicTable.innerHTML= "SORRY NO CHRONIC CLIENTS FOR NOW CHECK BACK LATER"
                }else{
                    const responesData = JSON.parse(ajaxRequest.responseText);
                    populateChronicTable(responesData);
                
                }
            }
        }
    }

    ajaxRequest.send();
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
                <span class='status danger'><a href='#' class='link_clear' onclick="deleteChronicUser(${clientdata.client_id}); return false;">Delete</a></span>
                <span class='status ontime'><a href='#' class='link_clear'>Details</a></span>
            </td>
        </tr>
        `;
        let newRow = chronicTable.insertRow(chronicTable.rows.length);
        newRow.innerHTML = tableData;
    });

}


// ============================ END ASYNC FUNCTION ==================================
// ACTIVATING THE RECOMMEND MODAL WHEN THE RECOMMEND BUTTON IS PRESSED;
function recommendCLient() {
    console.log(event.target)
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


// ============================ INDIVIDUAL DATE DISPLAYING ==================================
let dt = new Date();
const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
document.getElementById("datetime").innerHTML = dt.toDateString();
document.getElementById("printDate").innerHTML = dt.toDateString();
document.getElementById("day").innerHTML = days[dt.getDay()];