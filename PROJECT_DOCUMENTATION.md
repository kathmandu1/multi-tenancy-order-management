# Project Documentation - Multi-Tenant Order Management System

## 1. API and Database Design Choices

### API Design
- **RESTful structure** using Laravel's `Route::apiResource` for consistent naming and HTTP verb usage.
- **Multi-tenancy support** through a `X-Tenant` request header to ensure data isolation.
- **OpenAPI/Swagger** documentation for all endpoints with request/response examples.
- **Pagination** for list endpoints to handle large datasets efficiently.
- **Error handling** via Laravel's `FormRequest` validation and custom exception handlers.

**Example Endpoints:**
- `POST /api/tenants` – Create a new tenant.
- `POST /api/orders` – Create an order for a tenant.
- `GET /api/orders/{order}/tracking` – Retrieve order tracking info.

---

### Database Design
- **Separate databases per tenant** to ensure complete data isolation.
- **Main database** stores:
  - Tenants
- **Tenant databases** store:
  - Customers
  - Orders
  - Order Items
  - Products
  - Customer Shipping

- **Relationships**:
  - `Order` => `OrderItem` (One-to-Many)
  -  `Order`  =>  `Customer` (belongsTO)
  -  `Order` =>  `orderTrakings` (HasMany)
  - `Customer` => `Order` (One-to-Many)


**Key benefits**:
- Strong data isolation.
- Scalability for adding more tenants.

---

## 2. Design Patterns Used & Reasoning

- **Repository Pattern**  
  To abstract database queries from controllers, making code more testable and maintainable.

- **DTO (Data Transfer Object)**  
  Used for transferring structured request data across layers, reducing direct coupling between request objects and business logic.

- **Builder Pattern (DataBuilder classes)**  
  To construct complex DTOs from requests in a clean, reusable way.

- **Observer Pattern**  
  Automatically triggers related events (e.g., updating order status when items are created).

- **Service Layer**  
  Business logic is separated from controllers for better maintainability.

---

## 3. B2B/B2C Pricing Approach & Extension

**Current Implementation**:
- Pricing is determined by the `price_type` field in the `Customer` model:
  - **B2B** customers receive wholesale pricing.
  - **B2C** customers receive retail pricing.

**Extension Possibilities**:
- Tiered pricing levels for B2B customers based on purchase volume.
- Promotional discounts or coupon codes for B2C customers.
- Integration with external pricing engines for dynamic price adjustments.
- Currency and region-based pricing for international markets.



## 4. Use of AI Tools

- **Code Assistance**:  
  AI tools (e.g., ChatGPT) were used to:
  - Generate example Swagger documentation.
  - Suggest optimal database normalization techniques.
  - Draft clean code templates for DTOs and service layers.

- **Content Assistance**:  
  AI assisted in preparing developer documentation and API endpoint descriptions.

**Note**: All AI-generated code was reviewed and tested before inclusion.

---

## 5. Trade-offs, Known Limitations, and Next Steps

### Trade-offs
- **Separate DB per tenant** increases isolation but adds overhead when managing migrations and scaling infrastructure.
- **Swagger documentation** is static; dynamic updates require regenerating the file.

### Known Limitations
- Currently no bulk import/export feature for large datasets.
- No built-in analytics/dashboard for order trends.
- Limited real-time notifications for order updates.

### Suggested Next Steps
1. **Add background jobs** for heavy tasks (e.g., report generation).
2. **Implement event broadcasting** for real-time order tracking updates.
3. **Introduce caching** for frequently accessed API data.
4. **Add GraphQL layer** for more flexible querying in large B2B integrations.
5. **Enhance security** with role-based access control per tenant.

---

## 5. Branching Strategy and Tools for controll commit message and branch name (GrumPhp)
- Our branch name should followed by Feature/.. hotfix/... , please read file grumphp.yml in root of project
- commit message should followed by Feature #01... messae, Fixes....#cardNo comit message
- eg Fixes #01 fix on order api of order not creating
- Check code quality

**Author**: Santosh Ghimire  
**Date**: 2025-08-14  
**Version**: 1.0
