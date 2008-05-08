
-- Purpose: To allow gross sales and capital investment
--			greater than 999,999,999.99
--			To resolve problem found in Capas, Tarlac
-- Prepared by: Ron Crabtree (2008.05.08)
-- 
-- Database: 'ebpls'
-- 
-- Modify Table structure for table 'tempbusnature'
-- 

ALTER TABLE tempbusnature MODIFY last_yr decimal(12,2);
ALTER TABLE tempbusnature MODIFY cap_inv decimal(12,2);
