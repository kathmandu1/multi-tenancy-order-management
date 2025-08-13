

## About Multi Tenancy Order Management System

This multi-tenant order management system with a separate database per tenant, each tenant is distinctly identified by a unique tenant_id. This architecture ensures complete data isolation and security, as each tenant's products and orders reside in their own dedicated database. The system is designed to handle orders from creation to delivery, with a robust order tracking feature. When an order is placed, it is initially in a 'processing' state. As the order is fulfilled and leaves the warehouse, its status is updated to 'shipped'. The system provides real-time updates as the order moves through the logistics chain, and finally, the status changes to 'delivered' once the customer receives their package. This setup provides each tenant with a private and secure order management solution, while offering a comprehensive and transparent tracking experience for their customers. We have following Module:

- [Central Application (responsible for creating tenants)](https://github.com/kathmandu1/multi-tenancy-order-management/tree/main/Modules/CentralApplication).
- [Order Management (responseble for product, shipping and order management for tenants)](https://github.com/kathmandu1/multi-tenancy-order-management/tree/main/Modules/OrderManagement).

This Application Backend is Build on Laravel lastest version using Swagger-OpenAPI for APi docmentation

## Architecture and Design Pattern

The system's architecture is built on a clear separation of concerns, ensuring maintainability and scalability. Data transfer objects [(DTOs )](https://en.wikipedia.org/wiki/Data_transfer_object) and the DataBuilder pattern are employed for efficient data mapping, isolating the application's internal data structures from the data exposed to clients. This approach simplifies data transformation and enhances security by controlling which data fields are shared. Business logic is encapsulated within dedicated service classes, providing a single, consistent entry point for all operations and preventing code duplication. Data persistence is handled by the repository pattern, which abstracts the underlying data storage mechanism. This separation allows for flexible database changes without affecting the core application logic. Furthermore, the system adheres to the single responsibility principle, with each class designed to handle a specific, isolated functionality. This modular design minimizes dependencies, making the codebase easier to understand, test, and modify.



### DESIGN Pattern Used For Appication

- **[Pipeline Pattern ( For complex Search, Filter)](https://abc.com)**
- **[Repository Pattern (For chanagable data source and expandable Data abstration)](https://abc.com)**
- **[Service Pattern (Resuable and central business logic)](https://abc.com)**
- **[DTO Pattern (Add Data Security layer and Consistency which data exachanfe between layer)](https://abc.com)**
- **[TDD Pattern (Test each and individual componets of system and ensure that they work as expected)](https://abc.com)**


