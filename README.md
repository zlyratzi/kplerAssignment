# Project Overview

This project aims to provide solutions for managing customer data and invoices, as part of assignment project. It includes several routes and functionalities to facilitate data retrieval and manipulation.

## Key Routes

### Customers

- **Retrieve all customers:** `{home_project_url}/customers`
- **Filter customers by currency and country:** `{home_project_url}/customers?currency=eur&country=ai`
  - Accepted parameters for filtering:
  - `country`
  - `currency`
  - `orderBy`
  - `direction`

  - Note: The "country" filter uses a partial match (LIKE search) to find countries containing the specified value, rather than requiring an exact match.

### Invoices

- **Retrieve all invoices:** `{home_project_url}/invoices`
- **Retrieve invoices for a specific customer:** `{home_project_url}/invoices/{customer_id}`
- **Filter customer's invoices:** `{home_project_url}/invoices/{customer_id}?max_amount=2000&start_date=03-05-2020`
- **Retrieve information for a specific invoice:** `{home_project_url}/invoices/{invoice_id}/info`
- **Mark an invoice as paid:** `{home_project_url}/invoices/{invoice_id}/mark-as-paid`
- - Accepted parameters for filtering:
  - `start_date`
  - `end_date`
  - `date`
  - `amount`
  - `min_amount`
  - `max_amount`
  - `is_paid`
  - `orderBy`
  - `direction`

### Monthly Revenue Report

- **Monthly Revenue Report:** Renders a report displaying monthly revenue data.  
  `[Monthly Revenue Report]({home_project_url}/reports/monthly-revenue)`

- **Monthly Revenue Report CSV Download:** Downloads report data in CSV file.  
  `[Monthly Revenue Report CSV Download]({home_project_url}/monthly_revenue_csv_download)`

> In designing the database schema for the customers and invoices tables, I decided to utilize the default auto-increment field provided by the SQL database as the primary key. Rather than explicitly defining separate 'customer_id' and 'invoice_id' fields, I chose to designate them simply as unique identifiers within their respective entities, approaching the "code" logic. This approach streamlines database management and ensures consistency in data representation.
