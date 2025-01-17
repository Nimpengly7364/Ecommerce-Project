// Product Data
const products = [
    {
      id: "#001",
      name: "Keychain",
      price: 5,
      quantity: 4,
      image: "image/keychain9.jpg",
    },
    {
      id: "#002",
      name: "pin",
      price: 10,
      quantity: 2,
      image: "image/pin3.jpg",
    },
    {
      id: "#003",
      name: "Stickers",
      price: 10,
      quantity: 10,
      image: "image/sticker6.jpg",
    },
  ];
  
  // Dynamically Populate Cart Table
  const cartTableBody = document.getElementById("cart-table-body");
  
  // Function to render products in the cart table
  const renderCartTable = () => {
    cartTableBody.innerHTML = ""; // Clear table content before rendering
    let totalPrice = 0;
  
    products.forEach((product) => {
      // Calculate subtotal for each product
      const subtotal = product.price * product.quantity;
      totalPrice += subtotal;
  
      // Create table row
      const row = document.createElement("tr");
  
      // Product info cell
      const productCell = document.createElement("td");
      productCell.innerHTML = `
        <div class="product-info">
          <img src="${product.image}" alt="${product.name}" class="product-image">
          ${product.id} - ${product.name}
        </div>
      `;
      row.appendChild(productCell);
  
      // Price cell
      const priceCell = document.createElement("td");
      priceCell.textContent = `$${product.price}`;
      row.appendChild(priceCell);
  
      // Quantity cell
      const quantityCell = document.createElement("td");
      quantityCell.innerHTML = `
        <input type="number" value="${product.quantity}" class="quantity-input" min="1">
      `;
      // Update quantity on input change
      quantityCell.querySelector("input").addEventListener("change", (e) => {
        product.quantity = parseInt(e.target.value);
        renderCartTable(); // Re-render the table to update totals
      });
      row.appendChild(quantityCell);
  
      // Subtotal cell
      const subtotalCell = document.createElement("td");
      subtotalCell.textContent = `$${subtotal}`;
      subtotalCell.classList.add("subtotal");
      row.appendChild(subtotalCell);
  
      // Append row to table
      cartTableBody.appendChild(row);
    });
  
    // Update Cart Summary
    document.querySelector(".cart-summary p:nth-of-type(1)").textContent = `Subtotal: $${totalPrice}`;
    document.querySelector(".cart-summary p:nth-of-type(3)").textContent = `Total: $${totalPrice + 2}`;
  };
  
  // Initial rendering of the cart table
  renderCartTable();
