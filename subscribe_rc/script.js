const getSubscriptionAmount = () => {
    let amount_container = document.getElementById("amount");
    let msg_container = document.getElementById("msg");
    let url = "https://recordingclub.in/paymentGateway/getSubscriptionAmount.php";
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = () => {
      if (xhr.readyState == 4) {
          if (xhr.status == 200) {
              let my_response = JSON.parse(xhr.responseText);
              let amount = my_response.amount;
              let msg = amount_msg;
amount_container.innerHTML = "Rs. " + amount;
msg_container.innerHTML = msg;
          } else {
alert("Sorry user, We are not able to process the subscription this time.");
          }
      }
    };
    xhr.open("GET", url, true);
   xhr.send();
};

window.addEventListener("load", function () {
    getSubscriptionAmount();
});