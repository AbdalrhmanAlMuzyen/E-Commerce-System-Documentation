# 🛍️ E-Commerce System Documentation / Shoes-Store

Welcome to **Shoes-Store**, a full-featured e-commerce backend built with **Laravel 12**.  
This system provides a complete solution for online shopping, including user authentication, product and variant management, shopping cart, checkout with Stripe payment integration, order tracking, and advanced dashboard analytics.

_________________________________________________________________________________________

## 🛠️ Technologies & Architecture

- **Framework:** Laravel 12  
  Modern PHP framework providing a robust and scalable foundation for the project.

- **Architecture:** Clean Code & Layered Structure  
  The project follows a clear separation of concerns, divided into distinct layers:  
  1. **Controllers:** Handle HTTP requests and responses.  
  2. **Services:** Contain the business logic and orchestrate operations.  
  3. **Repositories:** Manage data access and queries from the database.  
  4. **DTOs (Data Transfer Objects):** Standardize data input for service operations.  
  5. **Models:** Represent database entities and relationships.  

- **Traits:**  
  Common reusable functionality is encapsulated in traits (e.g., `ReturnTrait` for standardized responses).

- **Policies:**  
  Authorization logic is handled using Laravel Policies to ensure secure access control at the model level.

_________________________________________________________________________________________

## 🧑‍💻 Authentication Flow

### 1️⃣ Register

- A new user account is created after validating the input data.
- For API/mobile users, a **refresh token** is generated and stored in the database.
- The refresh token is linked to:
  - the user
  - the device (`device_id`)
- Passwords are stored securely using hashing.

---

### 2️⃣ Login

User credentials are verified and a **JWT access token** is generated.

#### 🔹 Admin (Web)
- Receives a **JWT access token**.
- The token is stored in an **HTTP-only cookie** with:
  - `Secure`
  - `HttpOnly`
  - `SameSite=Strict`
- No refresh token is issued.
- When the token expires, the admin must log in again.

#### 🔹 User (Mobile / API)
- Receives a short-lived **JWT access token**.
- A **refresh token** is generated:
  - Stored securely in the database
  - Associated with the user and their device
- The refresh token allows obtaining new access tokens without re-authentication.

---

### 3️⃣ Refresh Token

- Used only by **mobile/API users**
- When the access token expires:
  1. The client sends the refresh token
  2. The server validates it
  3. A new JWT access token is issued
- If the refresh token is expired or invalid, the user must log in again.

---

### 4️⃣ Logout

- The logout process invalidates the current session by:
  - Deleting the refresh token from the database (for API users)
  - Removing authentication cookies (for web admins)
- Any existing JWT access token expires naturally after its short lifespan.

_________________________________________________________________________________________


## 🔐 Forgot & Reset Password

The application provides a secure and API-driven **Forgot / Reset Password** mechanism, designed to work seamlessly with web and mobile applications.

### 🔹 Forgot Password
When a user requests a password reset:

- The system verifies that the email belongs to an existing user.
- A unique reset code is generated for the user.
- The reset code is stored in the database with an expiration time.
- An email containing the reset code is sent to the user.
- The user uses this code inside the application to continue the password reset process.

This approach avoids exposing reset links and ensures that only the account owner with access to the email can initiate the process.

---

### 🔹 Reset Password
To complete the password reset:

- The user submits their email, reset code, and new password.
- The system validates:
  - The user exists.
  - A reset request is available.
  - The reset code is correct.
  - The reset code has not expired.
- If validation succeeds:
  - The user's password is updated securely.
  - All reset requests for the user are deleted to prevent reuse.
- A success response is returned confirming the password change.

---

### 🔹 Security Notes
- Reset codes are **single-use** and **time-limited**.
- Reset requests are removed after successful password updates.
- Invalid or expired reset codes are rejected.
- The flow is fully **API-based**, making it suitable for modern frontend frameworks such as React or Flutter.

_________________________________________________________________________________________

## 🌍 Locations Management

### Create Location
- Adds a new delivery location.  
- Accepts name and delivery fee.  
- Returns the created record with success message.

### Get All Locations
- Retrieves all delivery locations.  
- Returns 404 if no locations exist.

### Update Location
- Updates an existing location's name or delivery fee.  
- Skips update if no data is provided.  
- Returns updated record after successful update.

### Delete Location
- Deletes an existing location.  
- Verifies existence before deletion.

_________________________________________________________________________________________

## 🏷️ Categories Management

### Create Category
- Allows administrators to create a new category with an image.
- Accepts category name and image.  
- Image is uploaded to public storage, and path is saved with the category.

### Delete Category
- Deletes an existing category.  
- Related data is removed automatically based on cascade rules.

### Get All Categories
- Retrieves all categories ordered by popularity.  
- Each category includes the total number of products.  
- Sorted in descending order based on product count.

### Show Category Products & Statistics
- Displays all products in a category along with sales and stock analytics.
- For each product:
  - **Sales in last 20 days**: Only from `paid`, `shipped`, or `delivered` orders.  
  - **Total stock**: Sum of all related product variants.  
- Products with no sales are included.  
- Sorted by highest sales in the last 20 days, then by highest stock.

_________________________________________________________________________________________

## 🛍️ Products Management

### Create Product
- Adds a product under a specific category.  
- Requires category ID and product details (name, description, price).  
- Validates category existence.  
- Returns newly created product.

### Update Product
- Updates name, description, or price of a product.  
- Skips update if no changes provided.  
- Returns updated product.

### Delete Product
- Deletes an existing product by ID.  
- Validates existence before deletion.

_________________________________________________________________________________________

## 🎨 Colors Management

### Create Color
- Creates a new color record.  
- Accepts color details (name/value).  
- Returns the created record with a success message.

### Get All Colors
- Retrieves all available colors.  
- Returns a 404 if no colors exist.

### Delete Color
- Deletes an existing color by ID.  
- Verifies the color exists before deletion.

_________________________________________________________________________________________

## 📏 Sizes Management

### Create Size
- Adds a new size record.  
- Returns created size with success message.

### Get Sizes
- Retrieves all sizes.  
- Returns 404 if none exist.

### Delete Size
- Deletes a size by ID.  
- Returns success response.

_________________________________________________________________________________________

## 🧩 Product Variants Management

### Create Product Variant
- Adds a variant to an existing product.  
- Accepts size, color, total stock, and image URL.  
- Returns created variant with details.

### Get Product Variants
- Retrieves all variants of a product.  
- Indicates availability (`available` or `not available`) based on stock.  
- Includes related color and size info.

### Find Product Variant by ID
- Returns a single product variant by ID.  
- Returns null if not found.

### Update Product Variant
- Updates stock quantity (`total_stock`) of a variant.  
- Returns updated variant.

### Delete Product Variant
- Removes a product variant by reference.  
- Returns success status.

_________________________________________________________________________________________

## 🛒 Shopping Cart

The shopping cart allows authenticated users to manage products before placing an order.

- **Add Product to Cart**  
  - Verifies the product variant exists.  
  - Automatically creates a cart if the user has none.  
  - Updates quantity if the product variant already exists in the cart.  
  - Adds a new cart item if it doesn’t exist.

- **Remove Product from Cart**  
  - Ensures the cart item belongs to the current user before deletion.  
  - Deletes the item from the cart.

- **Update Product Quantity**  
  - Allows users to modify the quantity of items in their cart.

- **Get Cart Items**  
  - Returns all items in the user’s cart.  
  - If the cart is empty, an appropriate response is returned.

_________________________________________________________________________________________

## 🧾 Orders & Checkout Management

### Checkout
- Processes a user’s cart to create an order.  
- Validates stock and temporarily reserves it.  
- Calculates total price including delivery fee.  
- Creates order with items and payment intent.  
- Clears cart after order creation.  
- Returns `client_secret` for frontend payment processing.

### Handle Webhook
- Listens to Stripe events (`payment_intent.succeeded`, `payment_intent.payment_failed`, `payment_intent.canceled`).  
- Updates order status and stock based on payment outcome.

### Payment Handling
- **Succeeded**: Marks order as `paid`, releases reserved stock, updates payment record.  
- **Failed**: Marks order as `failed`, releases stock, records failure reason.  
- **Cancelled**: Marks order as `cancelled`, releases stock, updates payment record.

### My Orders
- Retrieves all orders for the authenticated user.  
- Returns a message if no orders exist.

### Update Order Status
- Updates an order’s status according to allowed transitions.  
- Prevents updates on cancelled orders.  
- Allowed transitions: `paid → shipped → delivered`.  
- Returns error for invalid transitions.

### Cancel Order
- Users can cancel pending orders.  
- Releases reserved stock.  
- Cancels associated payment intent.  
- Updates the order and payment record status.

### Get All Orders (Admin)
- Retrieves all orders in the system.  
- Returns message if no orders exist.  

### Stock Management
- Reserved stock prevents overselling.  
- Reserved stock is incremented during checkout.  
- Released after payment success, failure, or cancellation.

_________________________________________________________________________________________

## 📊 Dashboard & Analytics

### Orders Overview
- `total_orders`: Total number of orders.  
- Status breakdown: `pending`, `paid`, `shipped`, `delivered`, `cancelled`, `failed`.  
- `total_revenue`: Sum of delivered orders’ prices.  
- `average_order_value`: Average delivered order price.

### User Metrics
- `getTotalUsers()`: Count of registered users.  
- `getOneTimeCustomers()`: Users with exactly one completed order.  
- `getRepeatCustomers()`: Users with more than one completed order.  
- `getInactiveUsers()`: Users with no orders in last 30 days.  
- `getAverageRateBetweenOrders($user)`: Average days between consecutive orders.

### Product Metrics
- `getTopPurchasedProducts($user)`: Most purchased product by a user.  
- `getMostAddedButLeastOrdered()`: Products frequently added to carts but rarely ordered.

### Size Sales Overview
- `total_sold_quantity`: Sum of sold quantities per size.  
- Only considers orders with status `paid`, `shipped`, `delivered`.  
- Helps identify popular sizes for inventory planning.

### Notes on Data
- Analytics only considers orders with `paid`, `shipped`, `delivered` statuses.  
- Supports user-level and category/product-level reporting.

_________________________________________________________________________________________
