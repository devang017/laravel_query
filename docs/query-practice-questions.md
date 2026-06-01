# Laravel Advanced Eloquent and SQL Practice Questions

## Beginner

1. Fetch the latest 50 active users with their company and country using eager loading.
2. List all active products with their category name, sorted by newest first.
3. Retrieve a single order by order number with its user, items, item products, and payment.
4. Find all products where `stock_quantity` is less than 10 and status is active.
5. Count how many users exist for each status.
6. Use `withCount()` to list categories with the number of products in each category.
7. Use `withAvg()` to list products with their average review rating.
8. Use `withSum()` to show each order with the total quantity of its order items.
9. Load reviews for an already retrieved product using `load()`.
10. Demonstrate the N+1 problem when listing orders and users, then write the eager-loaded version.

## Intermediate

11. Find users who have at least one completed order using `whereHas()`.
12. Find products that have reviews with a rating of 4 or higher using `whereRelation()`.
13. Find companies that have active users from a specific country.
14. List parent categories with their child categories and child product counts.
15. Find products that belong to warehouses in a given city using the many-to-many relationship.
16. Retrieve orders where payment status is paid but the related payment row is refunded.
17. Use `whereDoesntHave()` to find users who have never placed an order.
18. Find products that have never been reviewed.
19. Fetch completed orders from the last 30 days with item count and payment details.
20. Compare lazy loading and eager loading query counts for orders and order items.

## Query Builder and Joins

21. Use an inner join to list order number, user email, payment method, and total amount.
22. Use a left join to find products without any order items.
23. Use a right join to list all categories and any matching products where supported by the database.
24. Use a cross join to generate a report matrix of all countries and order statuses.
25. Build a union query combining active users and blocked users into a single result set with a label column.
26. Join users, companies, countries, and orders to show completed revenue by company and country.
27. Join products, categories, order_items, and orders to rank category revenue.
28. Find warehouses with total inventory value using joins and aggregate multiplication.
29. List payment methods with total paid amount and failed payment count.
30. Find users whose latest order is cancelled using a join against a derived table.

## Advanced SQL

31. Use a subquery to select products whose price is above the average product price.
32. Use a correlated subquery to show each user with their latest order date.
33. Use `exists` to find products that have at least one completed order.
34. Use `not exists` to find active users without paid orders.
35. Use `group by` and `having` to find companies with more than 100 completed orders.
36. Find products whose total sold quantity is greater than the average sold quantity across all products.
37. Build a monthly revenue report for the last 36 months.
38. Find the highest revenue country for each industry.
39. Find users whose total completed order value is greater than their company average.
40. Find products reviewed by users who also purchased the product in a completed order.

## Performance

41. Process all users with `chunk()` and calculate how many are inactive per country.
42. Process all orders with `chunkById()` and export paid completed orders.
43. Stream all reviews with `cursor()` and calculate a running average rating per product.
44. Use `lazy()` to process products in memory-safe batches and identify low-stock products.
45. Build a cursor-paginated endpoint for repeat customers ordered by `id`.
46. Rewrite a slow report that uses lazy-loaded relationships into an eager-loaded version.
47. Compare `whereHas()` versus a join for finding products with completed orders.
48. Add a missing composite index for a slow query filtering orders by status, payment status, and created date.
49. Explain when `chunk()` can skip rows during updates and rewrite the process with `chunkById()`.
50. Design a seller-style performance report using company revenue, repeat customer rate, refund rate, and average order value.
