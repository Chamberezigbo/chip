// document.getElementById("custom-slider").addEventListener("input", function(event){
// 	let value = event.target.value;

// 	document.getElementById("current-value").innerText = value;

// 	document.getElementById("current-value").classList.add("active");

// 	document.getElementById("current-value").style.left = '${value/2}%';

// });

// document.getElementById("custom-slider").addEventListener("blur", function(event){
// 	document.getElementById("current-value").classList.remove("active");  
// });

function myFunction() {
  // Get the text field
  var copyText = document.getElementById("myInput");

  // Select the text field
  copyText.select();
  copyText.setSelectionRange(0, 99999); // For mobile devices

  // Copy the text inside the text field
  navigator.clipboard.writeText(copyText.value);
  
  // Alert the copied text
  alert("Wallet Address Copied : " + copyText.value);
}


function myWithdraw() {
  // alert("Withdrawal is being processed. You'll be notified shortly");
  if(!alert('Withdrawal is being processed. You will be notified shortly.')){window.location.reload();}
}


function myDeny() {
  // alert("Withdrawal is being processed. You'll be notified shortly");
  if(!alert('Transaction Denied')){window.location.reload();}
}

function myApp() {
  // alert("Withdrawal is being processed. You'll be notified shortly");
  if(!alert('Transaction Approved')){window.location.reload();}
}

function myUs() {
  // alert("Withdrawal is being processed. You'll be notified shortly");
  if(!alert('Details For this user has been updated.')){window.location.reload();}
}

function myProof() {
  // alert("Withdrawal is being processed. You'll be notified shortly");
  if(!alert('Payment Proof has been uploaded sucessfully.')){window.location.reload();}
}


const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))




