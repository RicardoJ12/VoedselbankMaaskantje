document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".btn-decrease").forEach((button) => {
    button.addEventListener("click", function () {
      updateQuantity(this.dataset.id, -1);
    });
  });

  document.querySelectorAll(".btn-increase").forEach((button) => {
    button.addEventListener("click", function () {
      updateQuantity(this.dataset.id, 1);
    });
  });

  function updateQuantity(productId, delta) {
    fetch("update_quantity.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id: productId, delta: delta }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          const productCard = document.querySelector(
            `.btn-decrease[data-id="${productId}"]`
          ).parentElement;
          const quantityElement = productCard.querySelector("p:nth-of-type(2)");
          quantityElement.textContent = data.newQuantity + "x";
        } else {
          alert("Failed to update quantity");
        }
      })
      .catch((error) => console.error("Error:", error));
  }
});
