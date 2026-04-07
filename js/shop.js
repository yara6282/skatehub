function showFilters(category) {
  const filtersDiv = document.getElementById("filters");
  filtersDiv.style.display = "block";

  let content = "";

  if (category === "skates") {
    content = `
      <h3>Skates Filters</h3>
      <label>Type:</label>
      <select>
        <option>Skateboard</option>
        <option>Longboard</option>
        <option>Inline</option>
        <option>Roller</option>
      </select>
      <label>Color:</label>
      <select>
        <option>Red</option>
        <option>Black</option>
        <option>White</option>
      </select>
    `;
  } else if (category === "tshirts") {
    content = `
      <h3>T-Shirts Filters</h3>
      <label>Size:</label>
      <select>
        <option>S</option>
        <option>M</option>
        <option>L</option>
        <option>XL</option>
        <option>XXL</option>
      </select>
      <label>Color:</label>
      <select>
        <option>Red</option>
        <option>Black</option>
        <option>White</option>
        <option>Blue</option>
      </select>
    `;
  } else if (category === "shoes") {
    content = `
      <h3>Shoes Filters</h3>
      <label>Size:</label>
      <select>
        <option>38</option>
        <option>39</option>
        <option>40</option>
        <option>41</option>
        <option>42</option>
        <option>43</option>
      </select>
      <label>Color:</label>
      <select>
        <option>Black</option>
        <option>White</option>
        <option>Gray</option>
      </select>
    `;
  } else if (category === "accessories") {
    content = `
      <h3>Accessories Filters</h3>
      <label>Type:</label>
      <select>
        <option>Stickers</option>
        <option>Keychains</option>
        <option>Medals</option>
      </select>
      <label>Color:</label>
      <select>
        <option>Red</option>
        <option>Black</option>
        <option>White</option>
      </select>
    `;
  }

  filtersDiv.innerHTML = content;
}

// افتراضياً يعرض فلاتر Skates
window.onload = function() {
  showFilters('skates');
};
function showFilters(category) {
  const filtersDiv = document.getElementById("filters");
  const productsDiv = document.getElementById("products");
  filtersDiv.style.display = "block";
  productsDiv.innerHTML = ""; // تفريغ المنتجات قبل إضافة جديدة

  let filtersContent = "";
  let productsContent = "";

  if (category === "skates") {
    filtersContent = `
      <h3>Skates Filters</h3>
      <label>Type:</label>
      <select>
        <option>Skateboard</option>
        <option>Longboard</option>
        <option>Inline</option>
        <option>Roller</option>
      </select>
      <label>Color:</label>
      <select>
        <option>Red</option>
        <option>Black</option>
        <option>White</option>
      </select>
    `;

    productsContent = `
      <div class="product-card">
        <img src="skateboard.jpg" alt="Skateboard">
        <h3>Skateboard</h3>
        <p>$120</p>
        <div class="product-btns">
          <button>Add to Cart</button>
          <button>Favorite</button>
        </div>
      </div>
      <div class="product-card">
        <img src="roller.jpg" alt="Roller Skates">
        <h3>Roller Skates</h3>
        <p>$90</p>
        <div class="product-btns">
          <button>Add to Cart</button>
          <button>Favorite</button>
        </div>
      </div>
    `;
  }

  else if (category === "tshirts") {
    filtersContent = `
      <h3>T-Shirts Filters</h3>
      <label>Size:</label>
      <select>
        <option>S</option>
        <option>M</option>
        <option>L</option>
        <option>XL</option>
        <option>XXL</option>
      </select>
      <label>Color:</label>
      <select>
        <option>Red</option>
        <option>Black</option>
        <option>White</option>
        <option>Blue</option>
      </select>
    `;

    productsContent = `
      <div class="product-card">
        <img src="tshirt1.jpg" alt="Classic Tee">
        <h3>Classic Tee</h3>
        <p>$25</p>
        <div class="product-btns">
          <button>Add to Cart</button>
          <button>Favorite</button>
        </div>
      </div>
      <div class="product-card">
        <img src="tshirt2.jpg" alt="Graphic Tee">
        <h3>Graphic Tee</h3>
        <p>$30</p>
        <div class="product-btns">
          <button>Add to Cart</button>
          <button>Favorite</button>
        </div>
      </div>
    `;
  }

  else if (category === "shoes") {
    filtersContent = `
      <h3>Shoes Filters</h3>
      <label>Size:</label>
      <select>
        <option>38</option>
        <option>39</option>
        <option>40</option>
        <option>41</option>
        <option>42</option>
        <option>43</option>
      </select>
      <label>Color:</label>
      <select>
        <option>Black</option>
        <option>White</option>
        <option>Gray</option>
      </select>
    `;

    productsContent = `
      <div class="product-card">
        <img src="shoes1.jpg" alt="Skate Shoes">
        <h3>Skate Shoes</h3>
        <p>$70</p>
        <div class="product-btns">
          <button>Add to Cart</button>
          <button>Favorite</button>
        </div>
      </div>
      <div class="product-card">
        <img src="shoes2.jpg" alt="High Tops">
        <h3>High Tops</h3>
        <p>$85</p>
        <div class="product-btns">
          <button>Add to Cart</button>
          <button>Favorite</button>
        </div>
      </div>
    `;
  }

  else if (category === "accessories") {
    filtersContent = `
      <h3>Accessories Filters</h3>
      <label>Type:</label>
      <select>
        <option>Stickers</option>
        <option>Keychains</option>
        <option>Medals</option>
      </select>
      <label>Color:</label>
      <select>
        <option>Red</option>
        <option>Black</option>
        <option>White</option>
      </select>
    `;

    productsContent = `
      <div class="product-card">
        <img src="sticker1.jpg" alt="Sticker Pack">
        <h3>Sticker Pack</h3>
        <p>$10</p>
        <div class="product-btns">
          <button>Add to Cart</button>
          <button>Favorite</button>
        </div>
      </div>
      <div class="product-card">
        <img src="keychain.jpg" alt="Skate Keychain">
        <h3>Skate Keychain</h3>
        <p>$12</p>
        <div class="product-btns">
          <button>Add to Cart</button>
          <button>Favorite</button>
        </div>
      </div>
    `;
  }

  filtersDiv.innerHTML = filtersContent;
  productsDiv.innerHTML = productsContent;
}

// افتراضياً يعرض Skates
window.onload = function() {
  showFilters('skates');
};
function showFilters(category) {
  const filtersDiv = document.getElementById("filters");
  const productsDiv = document.getElementById("products");
  filtersDiv.style.display = "block";
  productsDiv.innerHTML = "";

  let filtersContent = "";
  let productsContent = "";

  if (category === "skates") {
    filtersContent = `
      <h3>Skates Filters</h3>
      <label>Type:</label>
      <select>
        <option>Skateboard</option>
        <option>Longboard</option>
        <option>Inline</option>
        <option>Roller</option>
      </select>
      <label>Color:</label>
      <select>
        <option>Red</option>
        <option>Black</option>
        <option>White</option>
      </select>
    `;
    productsContent = `
      <div class="product-card"><img src="skateboard.jpg" alt="Skateboard"><h3>Skateboard</h3><p>$120</p><div class="product-btns">
    <button onclick="addToCart('Skateboard')">Add to Cart</button>
    <button onclick="addToFavorites('Skateboard')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="roller.jpg" alt="Roller Skates"><h3>Roller Skates</h3><p>$90</p><div class="product-btns">
    <button onclick="addToCart('Roller Skates')">Add to Cart</button>
    <button onclick="addToFavorites('Roller Skates')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="longboard.jpg" alt="Longboard"><h3>Longboard</h3><p>$150</p><div class="product-btns">
    <button onclick="addToCart('Longboard')">Add to Cart</button>
    <button onclick="addToFavorites('Longboard')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="inline.jpg" alt="Inline Skates"><h3>Inline Skates</h3><p>$110</p><div class="product-btns">
    <button onclick="addToCart('Inline Skates')">Add to Cart</button>
    <button onclick="addToFavorites('Inline Skates')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="cruiser.jpg" alt="Cruiser Board"><h3>Cruiser Board</h3><p>$130</p><div class="product-btns">
    <button onclick="addToCart('Cruiser Board')">Add to Cart</button>
    <button onclick="addToFavorites('Cruiser Board')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="proskate.jpg" alt="Pro Skate"><h3>Pro Skate</h3><p>$200</p><div class="product-btns">
    <button onclick="addToCart('Pro Skate')">Add to Cart</button>
    <button onclick="addToFavorites('Pro Skate')">Favorite</button>
  </div></div>
    `;
  }

  else if (category === "tshirts") {
    filtersContent = `
      <h3>T-Shirts Filters</h3>
      <label>Size:</label>
      <select><option>S</option><option>M</option><option>L</option><option>XL</option><option>XXL</option></select>
      <label>Color:</label>
      <select><option>Red</option><option>Black</option><option>White</option><option>Blue</option></select>
    `;
    productsContent = `
      <div class="product-card"><img src="tshirt1.jpg" alt="Classic Tee"><h3>Classic Tee</h3><p>$25</p><div class="product-btns">
    <button onclick="addToCart('Classic Tee')">Add to Cart</button>
    <button onclick="addToFavorites('Classic Tee')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="tshirt2.jpg" alt="Graphic Tee"><h3>Graphic Tee</h3><p>$30</p><div class="product-btns">
    <button onclick="addToCart('Graphic Tee')">Add to Cart</button>
    <button onclick="addToFavorites('Graphic Tee')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="tshirt3.jpg" alt="Logo Tee"><h3>Logo Tee</h3><p>$28</p><div class="product-btns">
    <button onclick="addToCart('Logo Tee')">Add to Cart</button>
    <button onclick="addToFavorites('Logo Tee')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="tshirt4.jpg" alt="Vintage Tee"><h3>Vintage Tee</h3><p>$35</p><div class="product-btns">
    <button onclick="addToCart('Vintage Tee')">Add to Cart</button>
    <button onclick="addToFavorites('Vintage Tee')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="tshirt5.jpg" alt="Sport Tee"><h3>Sport Tee</h3><p>$32</p><div class="product-btns">
    <button onclick="addToCart('Sport Tee')">Add to Cart</button>
    <button onclick="addToFavorites('Sport Tee')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="tshirt6.jpg" alt="Oversized Tee"><h3>Oversized Tee</h3><p>$40</p><div class="product-btns">
    <button onclick="addToCart('Oversized Tee')">Add to Cart</button>
    <button onclick="addToFavorites('Oversized Tee')">Favorite</button>
  </div></div>
    `;
  }

  else if (category === "shoes") {
    filtersContent = `
      <h3>Shoes Filters</h3>
      <label>Size:</label>
      <select><option>38</option><option>39</option><option>40</option><option>41</option><option>42</option><option>43</option></select>
      <label>Color:</label>
      <select><option>Black</option><option>White</option><option>Gray</option></select>
    `;
    productsContent = `
      <div class="product-card"><img src="shoes1.jpg" alt="Skate Shoes"><h3>Skate Shoes</h3><p>$70</p><div class="product-btns">
    <button onclick="addToCart('Skate Shoes')">Add to Cart</button>
    <button onclick="addToFavorites('Skate Shoes')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="shoes2.jpg" alt="High Tops"><h3>High Tops</h3><p>$85</p><div class="product-btns">
    <button onclick="addToCart('High Tops')">Add to Cart</button>
    <button onclick="addToFavorites('High Tops')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="shoes3.jpg" alt="Slip-ons"><h3>Slip-ons</h3><p>$60</p><div class="product-btns">
    <button onclick="addToCart('Slip-ons')">Add to Cart</button>
    <button onclick="addToFavorites('Slip-ons')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="shoes4.jpg" alt="Canvas Shoes"><h3>Canvas Shoes</h3><p>$75</p><div class="product-btns">
    <button onclick="addToCart('Canvas Shoes')">Add to Cart</button>
    <button onclick="addToFavorites('Canvas Shoes')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="shoes5.jpg" alt="Leather Shoes"><h3>Leather Shoes</h3><p>$95</p><div class="product-btns">
    <button onclick="addToCart('Leather Shoes')">Add to Cart</button>
    <button onclick="addToFavorites('Leather Shoes')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="shoes6.jpg" alt="Pro Skate Shoes"><h3>Pro Skate Shoes</h3><p>$120</p><div class="product-btns">
    <button onclick="addToCart('Pro Skate Shoes')">Add to Cart</button>
    <button onclick="addToFavorites('Pro Skate Shoes')">Favorite</button>
  </div></div>
    `;
  }

  else if (category === "accessories") {
    filtersContent = `
      <h3>Accessories Filters</h3>
      <label>Type:</label>
      <select><option>Stickers</option><option>Keychains</option><option>Medals</option></select>
      <label>Color:</label>
      <select><option>Red</option><option>Black</option><option>White</option></select>
    `;
    productsContent = `
      <div class="product-card"><img src="sticker1.jpg" alt="Sticker Pack"><h3>Sticker Pack</h3><p>$10</p><div class="product-btns">
    <button onclick="addToCart('Sticker Pack')">Add to Cart</button>
    <button onclick="addToFavorites('Sticker Pack')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="keychain.jpg" alt="Skate Keychain"><h3>Skate Keychain</h3><p>$12</p><div class="product-btns">
    <button onclick="addToCart('Skate Keychain')">Add to Cart</button>
    <button onclick="addToFavorites('Skate Keychain')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="medal.jpg" alt="Medal"><h3>Medal</h3><p>$15</p><div class="product-btns">
    <button onclick="addToCart('Medal')">Add to Cart</button>
    <button onclick="addToFavorites('Medal')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="cap.jpg" alt="Skate Cap"><h3>Skate Cap</h3><p>$20</p><div class="product-btns">
    <button onclick="addToCart('Skate Cap')">Add to Cart</button>
    <button onclick="addToFavorites('Skate Cap')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="bag.jpg" alt="Skate Bag"><h3>Skate Bag</h3><p>$45</p><div class="product-btns">
    <button onclick="addToCart('Skate Bag')">Add to Cart</button>
    <button onclick="addToFavorites('Skate Bag')">Favorite</button>
  </div></div>
      <div class="product-card"><img src="gloves.jpg" alt="Skate Gloves"><h3>Skate Gloves</h3><p>$18</p><div class="product-btns">
    <button onclick="addToCart('Skate Gloves')">Add to Cart</button>
    <button onclick="addToFavorites('Skate Gloves')">Favorite</button>
  </div></div>
    `;
  }

  filtersDiv.innerHTML = filtersContent;
  productsDiv.innerHTML = productsContent;
}

window.onload = function() {
  showFilters('skates');
};
function addToCart(productName) {
  alert(productName + " تمت إضافته للسلة 🛒");
}

function addToFavorites(productName) {
  alert(productName + " تمت إضافته للمفضلة ❤️");
}
