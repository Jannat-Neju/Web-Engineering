<?php
@include 'config.php';

// ✅ Start session
if (session_status() === PHP_SESSION_NONE) {
   session_start();
}

$user_id = $_SESSION['user_id'] ?? null;

// ✅ Initialize defaults
$user = null;
$wishlist_num_rows = 0;
$cart_num_rows = 0;

// ✅ Initialize guest wishlist/cart sessions if not exist
if (!isset($_SESSION['wishlist'])) {
   $_SESSION['wishlist'] = [];
}
if (!isset($_SESSION['cart'])) {
   $_SESSION['cart'] = [];
}

if ($user_id) {
   // ✅ Logged-in user
   $user_query = mysqli_query($conn, "SELECT name, email, image FROM users WHERE id = '$user_id'") or die('User query failed');
   $user = mysqli_fetch_assoc($user_query);

   // ✅ Count wishlist (from DB)
   $wishlist_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `wishlist` WHERE user_id = '$user_id'") or die('Wishlist query failed');
   $wishlist_data = mysqli_fetch_assoc($wishlist_result);
   $wishlist_num_rows = $wishlist_data['total'];

   // ✅ Count cart (from DB)
   $cart_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `cart` WHERE user_id = '$user_id'") or die('Cart query failed');
   $cart_data = mysqli_fetch_assoc($cart_result);
   $cart_num_rows = $cart_data['total'];

} else {
   // ✅ Guest user – count session wishlist/cart
   $wishlist_num_rows = count($_SESSION['wishlist']);
   $cart_num_rows = count($_SESSION['cart']);
}
?>

<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>';
   }
}
?>

<header class="header">
   <div class="flex">
      <a href="home.php" class="logo">
         <img src="teaimg/download.png" alt="Logo" class="logo-img">
         <span>BotaniQ.</span>
      </a>

      <style>
     /* Header Logo */
.logo {
    display: flex;
    align-items: center;
    text-decoration: none;
    font-size: 24px;
    font-weight: 700;
    color: #008000;
}
.logo-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
    object-fit: cover;
}
.logo:hover {
    color: #056105;
}

/* Icons container */
.icons {
    display: flex;
    align-items: center;
    gap: 2px;
    position: relative;
}

/* Icons style */
.icons a {
    color: #333;
    text-decoration: none;
    font-size: 15px;
    position: relative;
}
.icons span {
    font-size: 14px;
    margin-left: 4px;
}

/* User dropdown */
.user-dropdown {
    position: relative;
    display: inline-block;
}
.dropdown-toggle {
    display: flex;
    align-items: center;
    cursor: pointer;
}
.dropdown-menu {
    position: absolute;
    top: 120%;
    right: 0;
    background: #fff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    padding: 10px 0;
    min-width: 180px;
    display: none; /* hidden by default */
    z-index: 100;
    border-radius: 5px;
}
.dropdown-menu.show {
    display: block; /* show when toggled */
}
.dropdown-menu li {
    list-style: none;
}
.dropdown-menu li a {
    display: block;
    padding: 10px 16px;
    color: #333;
    text-decoration: none;
    transition: background 0.3s;
}
.dropdown-menu li a:hover {
    background-color: #f0f0f0;
}
.dropdown-header {
    padding: 10px 16px;
    font-weight: bold;
    border-bottom: 1px solid #ddd;
    color: #008000;
}

/* User avatar */
.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #008000;
}

      </style>

      <nav class="navbar">
         <ul>
            <li><a href="home.php">home</a></li>
            <li><a href="#">pages +</a>
               <ul>
                  <li><a href="about.php">about</a></li>
                  <li><a href="contact.php">contact</a></li>
               </ul>
            </li>
            <li><a href="shop.php">shop</a></li>
            <li><a href="#">account +</a>
               <ul>
                  <?php if ($user_id): ?>
                     <li><a href="logout.php">Logout</a></li>
                  <?php else: ?>
                     <li><a href="login.php">Login</a></li>
                     <li><a href="register.php">Register</a></li>
                  <?php endif; ?>
               </ul>
            </li>
         </ul>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <a href="search_page.php" class="fas fa-search"></a>

         <!-- Wishlist icon -->
         <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?php echo $wishlist_num_rows; ?>)</span></a>

         <!-- Cart icon -->
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?php echo $cart_num_rows; ?>)</span></a>

         <!-- User avatar dropdown -->
         <?php if ($user_id): ?>
         <div class="user-dropdown">
            <div class="dropdown-toggle">
               <img src="<?php echo !empty($user['image']) ? 'uploaded_images/' . $user['image'] : 'images/default-avatar.png'; ?>" 
                    alt="Profile" class="user-avatar">
            </div>
            <ul class="dropdown-menu">
               <li class="dropdown-header"><?php echo htmlspecialchars($user['name']); ?></li>
               <li><a href="my_profile.php">My Profile</a></li>
               <li><a href="logout.php">Logout</a></li>
            </ul>
         </div>
         <script>
// Select dropdown toggle and menu
const dropdownToggle = document.querySelector('.dropdown-toggle');
const dropdownMenu = document.querySelector('.dropdown-menu');

// Toggle dropdown on click
dropdownToggle.addEventListener('click', function(e) {
    e.stopPropagation(); // prevent document click from closing it immediately
    dropdownMenu.classList.toggle('show');
});

// Close dropdown if clicked outside
document.addEventListener('click', function() {
    dropdownMenu.classList.remove('show');
});

// Hamburger menu toggle
const menuBtn = document.querySelector('#menu-btn');
const navbar = document.querySelector('.navbar');

menuBtn.addEventListener('click', function (e) {
    e.stopPropagation();
    navbar.classList.toggle('active');
});

// Close menu when clicking outside
document.addEventListener('click', function () {
    navbar.classList.remove('active');
});


</script>

         <?php endif; ?>
      </div>
   </div>
</header>
