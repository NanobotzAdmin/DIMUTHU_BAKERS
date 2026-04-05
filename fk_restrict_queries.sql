-- 1. Remove the existing cascading constraint for ad_agent_has_bank_accounts
ALTER TABLE `ad_agent_has_bank_accounts` 
DROP FOREIGN KEY `ad_agent_has_bank_accounts_agent_id_foreign`;

-- 2. Add the new restrictive constraint for ad_agent_has_bank_accounts
ALTER TABLE `ad_agent_has_bank_accounts` 
ADD CONSTRAINT `ad_agent_has_bank_accounts_agent_id_foreign` 
FOREIGN KEY (`agent_id`) REFERENCES `ad_agent`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for ad_customer_has_business
ALTER TABLE `ad_customer_has_business` 
DROP FOREIGN KEY `ad_customer_has_business_customer_id_foreign`;

-- 2. Add the new restrictive constraint for ad_customer_has_business
ALTER TABLE `ad_customer_has_business` 
ADD CONSTRAINT `ad_customer_has_business_customer_id_foreign` 
FOREIGN KEY (`customer_id`) REFERENCES `cm_customer`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for ad_daily_loads
ALTER TABLE `ad_daily_loads` 
DROP FOREIGN KEY `ad_daily_loads_agent_id_foreign`;

-- 2. Add the new restrictive constraint for ad_daily_loads
ALTER TABLE `ad_daily_loads` 
ADD CONSTRAINT `ad_daily_loads_agent_id_foreign` 
FOREIGN KEY (`agent_id`) REFERENCES `ad_agent`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for ad_daily_loads_has_product_items
ALTER TABLE `ad_daily_loads_has_product_items` 
DROP FOREIGN KEY `ad_daily_loads_has_product_items_stm_branch_stock_id_foreign`;

-- 2. Add the new restrictive constraint for ad_daily_loads_has_product_items
ALTER TABLE `ad_daily_loads_has_product_items` 
ADD CONSTRAINT `ad_daily_loads_has_product_items_stm_branch_stock_id_foreign` 
FOREIGN KEY (`stm_branch_stock_id`) REFERENCES `stm_branch_stock`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for ad_daily_loads
ALTER TABLE `ad_daily_loads` 
DROP FOREIGN KEY `ad_daily_loads_route_id_foreign`;

-- 2. Add the new restrictive constraint for ad_daily_loads
ALTER TABLE `ad_daily_loads` 
ADD CONSTRAINT `ad_daily_loads_route_id_foreign` 
FOREIGN KEY (`route_id`) REFERENCES `ad_routes`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for ad_route_has_customers
ALTER TABLE `ad_route_has_customers` 
DROP FOREIGN KEY `ad_route_has_customers_customer_id_foreign`;

-- 2. Add the new restrictive constraint for ad_route_has_customers
ALTER TABLE `ad_route_has_customers` 
ADD CONSTRAINT `ad_route_has_customers_customer_id_foreign` 
FOREIGN KEY (`customer_id`) REFERENCES `cm_customer`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for ad_route_has_customers
ALTER TABLE `ad_route_has_customers` 
DROP FOREIGN KEY `ad_route_has_customers_route_id_foreign`;

-- 2. Add the new restrictive constraint for ad_route_has_customers
ALTER TABLE `ad_route_has_customers` 
ADD CONSTRAINT `ad_route_has_customers_route_id_foreign` 
FOREIGN KEY (`route_id`) REFERENCES `ad_routes`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for ad_daily_loads_has_product_items
ALTER TABLE `ad_daily_loads_has_product_items` 
DROP FOREIGN KEY `daily_load_fk`;

-- 2. Add the new restrictive constraint for ad_daily_loads_has_product_items
ALTER TABLE `ad_daily_loads_has_product_items` 
ADD CONSTRAINT `daily_load_fk` 
FOREIGN KEY (`daily_load_id`) REFERENCES `ad_daily_loads`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pln_production_schedules
ALTER TABLE `pln_production_schedules` 
DROP FOREIGN KEY `pln_production_schedules_pln_department_id_foreign`;

-- 2. Add the new restrictive constraint for pln_production_schedules
ALTER TABLE `pln_production_schedules` 
ADD CONSTRAINT `pln_production_schedules_pln_department_id_foreign` 
FOREIGN KEY (`pln_department_id`) REFERENCES `pln_departments`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pln_production_schedules
ALTER TABLE `pln_production_schedules` 
DROP FOREIGN KEY `pln_production_schedules_pln_resource_id_foreign`;

-- 2. Add the new restrictive constraint for pln_production_schedules
ALTER TABLE `pln_production_schedules` 
ADD CONSTRAINT `pln_production_schedules_pln_resource_id_foreign` 
FOREIGN KEY (`pln_resource_id`) REFERENCES `pln_resources`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pln_production_schedules
ALTER TABLE `pln_production_schedules` 
DROP FOREIGN KEY `pln_production_schedules_um_branch_id_foreign`;

-- 2. Add the new restrictive constraint for pln_production_schedules
ALTER TABLE `pln_production_schedules` 
ADD CONSTRAINT `pln_production_schedules_um_branch_id_foreign` 
FOREIGN KEY (`um_branch_id`) REFERENCES `um_branch`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pln_resources
ALTER TABLE `pln_resources` 
DROP FOREIGN KEY `pln_resources_pln_department_id_foreign`;

-- 2. Add the new restrictive constraint for pln_resources
ALTER TABLE `pln_resources` 
ADD CONSTRAINT `pln_resources_pln_department_id_foreign` 
FOREIGN KEY (`pln_department_id`) REFERENCES `pln_departments`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pln_schedules_has_instructions
ALTER TABLE `pln_schedules_has_instructions` 
DROP FOREIGN KEY `pln_schedules_has_instructions_instruction_id_foreign`;

-- 2. Add the new restrictive constraint for pln_schedules_has_instructions
ALTER TABLE `pln_schedules_has_instructions` 
ADD CONSTRAINT `pln_schedules_has_instructions_instruction_id_foreign` 
FOREIGN KEY (`instruction_id`) REFERENCES `pm_recipe_instructions`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pln_schedules_has_instructions
ALTER TABLE `pln_schedules_has_instructions` 
DROP FOREIGN KEY `pln_schedules_has_instructions_production_schedule_id_foreign`;

-- 2. Add the new restrictive constraint for pln_schedules_has_instructions
ALTER TABLE `pln_schedules_has_instructions` 
ADD CONSTRAINT `pln_schedules_has_instructions_production_schedule_id_foreign` 
FOREIGN KEY (`production_schedule_id`) REFERENCES `pln_production_schedules`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_brands
ALTER TABLE `pm_brands` 
DROP FOREIGN KEY `pm_brands_created_by_foreign`;

-- 2. Add the new restrictive constraint for pm_brands
ALTER TABLE `pm_brands` 
ADD CONSTRAINT `pm_brands_created_by_foreign` 
FOREIGN KEY (`created_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_interfaces
ALTER TABLE `pm_interfaces` 
DROP FOREIGN KEY `pm_interfaces_pm_interface_topic_id_foreign`;

-- 2. Add the new restrictive constraint for pm_interfaces
ALTER TABLE `pm_interfaces` 
ADD CONSTRAINT `pm_interfaces_pm_interface_topic_id_foreign` 
FOREIGN KEY (`pm_interface_topic_id`) REFERENCES `pm_interface_topic`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_interface_components
ALTER TABLE `pm_interface_components` 
DROP FOREIGN KEY `pm_interface_components_pm_interface_id_foreign`;

-- 2. Add the new restrictive constraint for pm_interface_components
ALTER TABLE `pm_interface_components` 
ADD CONSTRAINT `pm_interface_components_pm_interface_id_foreign` 
FOREIGN KEY (`pm_interface_id`) REFERENCES `pm_interfaces`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_interface_component_history
ALTER TABLE `pm_interface_component_history` 
DROP FOREIGN KEY `pm_interface_component_history_pm_user_role_id_foreign`;

-- 2. Add the new restrictive constraint for pm_interface_component_history
ALTER TABLE `pm_interface_component_history` 
ADD CONSTRAINT `pm_interface_component_history_pm_user_role_id_foreign` 
FOREIGN KEY (`pm_user_role_id`) REFERENCES `pm_user_role`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_interface_component_history
ALTER TABLE `pm_interface_component_history` 
DROP FOREIGN KEY `pm_interface_component_history_um_user_id_foreign`;

-- 2. Add the new restrictive constraint for pm_interface_component_history
ALTER TABLE `pm_interface_component_history` 
ADD CONSTRAINT `pm_interface_component_history_um_user_id_foreign` 
FOREIGN KEY (`um_user_id`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_interface_component_history
ALTER TABLE `pm_interface_component_history` 
DROP FOREIGN KEY `pm_int_comp_hist_comp_id_foreign`;

-- 2. Add the new restrictive constraint for pm_interface_component_history
ALTER TABLE `pm_interface_component_history` 
ADD CONSTRAINT `pm_int_comp_hist_comp_id_foreign` 
FOREIGN KEY (`pm_interface_components_id`) REFERENCES `pm_interface_components`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_product
ALTER TABLE `pm_product` 
DROP FOREIGN KEY `pm_product_created_by_foreign`;

-- 2. Add the new restrictive constraint for pm_product
ALTER TABLE `pm_product` 
ADD CONSTRAINT `pm_product_created_by_foreign` 
FOREIGN KEY (`created_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_product_images
ALTER TABLE `pm_product_images` 
DROP FOREIGN KEY `pm_product_images_created_by_foreign`;

-- 2. Add the new restrictive constraint for pm_product_images
ALTER TABLE `pm_product_images` 
ADD CONSTRAINT `pm_product_images_created_by_foreign` 
FOREIGN KEY (`created_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_product_images
ALTER TABLE `pm_product_images` 
DROP FOREIGN KEY `pm_product_images_pm_product_id_foreign`;

-- 2. Add the new restrictive constraint for pm_product_images
ALTER TABLE `pm_product_images` 
ADD CONSTRAINT `pm_product_images_pm_product_id_foreign` 
FOREIGN KEY (`pm_product_id`) REFERENCES `pm_product`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_product_item
ALTER TABLE `pm_product_item` 
DROP FOREIGN KEY `pm_product_item_created_by_foreign`;

-- 2. Add the new restrictive constraint for pm_product_item
ALTER TABLE `pm_product_item` 
ADD CONSTRAINT `pm_product_item_created_by_foreign` 
FOREIGN KEY (`created_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_product_item_has_product_types
ALTER TABLE `pm_product_item_has_product_types` 
DROP FOREIGN KEY `pm_product_item_has_product_types_product_item_id_foreign`;

-- 2. Add the new restrictive constraint for pm_product_item_has_product_types
ALTER TABLE `pm_product_item_has_product_types` 
ADD CONSTRAINT `pm_product_item_has_product_types_product_item_id_foreign` 
FOREIGN KEY (`product_item_id`) REFERENCES `pm_product_item`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_product_item_has_product_types
ALTER TABLE `pm_product_item_has_product_types` 
DROP FOREIGN KEY `pm_product_item_has_product_types_product_type_id_foreign`;

-- 2. Add the new restrictive constraint for pm_product_item_has_product_types
ALTER TABLE `pm_product_item_has_product_types` 
ADD CONSTRAINT `pm_product_item_has_product_types_product_type_id_foreign` 
FOREIGN KEY (`product_type_id`) REFERENCES `pm_product_type`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_product_item
ALTER TABLE `pm_product_item` 
DROP FOREIGN KEY `pm_product_item_pm_brands_id_foreign`;

-- 2. Add the new restrictive constraint for pm_product_item
ALTER TABLE `pm_product_item` 
ADD CONSTRAINT `pm_product_item_pm_brands_id_foreign` 
FOREIGN KEY (`pm_brands_id`) REFERENCES `pm_brands`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_product_item
ALTER TABLE `pm_product_item` 
DROP FOREIGN KEY `pm_product_item_pm_product_id_foreign`;

-- 2. Add the new restrictive constraint for pm_product_item
ALTER TABLE `pm_product_item` 
ADD CONSTRAINT `pm_product_item_pm_product_id_foreign` 
FOREIGN KEY (`pm_product_id`) REFERENCES `pm_product`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_product_item
ALTER TABLE `pm_product_item` 
DROP FOREIGN KEY `pm_product_item_pm_variation_id_foreign`;

-- 2. Add the new restrictive constraint for pm_product_item
ALTER TABLE `pm_product_item` 
ADD CONSTRAINT `pm_product_item_pm_variation_id_foreign` 
FOREIGN KEY (`pm_variation_id`) REFERENCES `pm_variation`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_product_item
ALTER TABLE `pm_product_item` 
DROP FOREIGN KEY `pm_product_item_pm_variation_value_id_foreign`;

-- 2. Add the new restrictive constraint for pm_product_item
ALTER TABLE `pm_product_item` 
ADD CONSTRAINT `pm_product_item_pm_variation_value_id_foreign` 
FOREIGN KEY (`pm_variation_value_id`) REFERENCES `pm_variation_value`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_product_item
ALTER TABLE `pm_product_item` 
DROP FOREIGN KEY `pm_product_item_updated_by_foreign`;

-- 2. Add the new restrictive constraint for pm_product_item
ALTER TABLE `pm_product_item` 
ADD CONSTRAINT `pm_product_item_updated_by_foreign` 
FOREIGN KEY (`updated_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_recipe_byproducts
ALTER TABLE `pm_recipe_byproducts` 
DROP FOREIGN KEY `pm_recipe_byproducts_recipe_id_foreign`;

-- 2. Add the new restrictive constraint for pm_recipe_byproducts
ALTER TABLE `pm_recipe_byproducts` 
ADD CONSTRAINT `pm_recipe_byproducts_recipe_id_foreign` 
FOREIGN KEY (`recipe_id`) REFERENCES `pm_recipes`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_recipe_byproduct_nrvs
ALTER TABLE `pm_recipe_byproduct_nrvs` 
DROP FOREIGN KEY `pm_recipe_byproduct_nrvs_recipe_byproduct_id_foreign`;

-- 2. Add the new restrictive constraint for pm_recipe_byproduct_nrvs
ALTER TABLE `pm_recipe_byproduct_nrvs` 
ADD CONSTRAINT `pm_recipe_byproduct_nrvs_recipe_byproduct_id_foreign` 
FOREIGN KEY (`recipe_byproduct_id`) REFERENCES `pm_recipe_byproducts`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_recipe_ingredients
ALTER TABLE `pm_recipe_ingredients` 
DROP FOREIGN KEY `pm_recipe_ingredients_recipe_id_foreign`;

-- 2. Add the new restrictive constraint for pm_recipe_ingredients
ALTER TABLE `pm_recipe_ingredients` 
ADD CONSTRAINT `pm_recipe_ingredients_recipe_id_foreign` 
FOREIGN KEY (`recipe_id`) REFERENCES `pm_recipes`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_recipe_instructions
ALTER TABLE `pm_recipe_instructions` 
DROP FOREIGN KEY `pm_recipe_instructions_recipe_id_foreign`;

-- 2. Add the new restrictive constraint for pm_recipe_instructions
ALTER TABLE `pm_recipe_instructions` 
ADD CONSTRAINT `pm_recipe_instructions_recipe_id_foreign` 
FOREIGN KEY (`recipe_id`) REFERENCES `pm_recipes`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_user_role_has_interface_components
ALTER TABLE `pm_user_role_has_interface_components` 
DROP FOREIGN KEY `pm_urhic_pm_ic_id_foreign`;

-- 2. Add the new restrictive constraint for pm_user_role_has_interface_components
ALTER TABLE `pm_user_role_has_interface_components` 
ADD CONSTRAINT `pm_urhic_pm_ic_id_foreign` 
FOREIGN KEY (`pm_interface_components_id`) REFERENCES `pm_interface_components`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_user_role_has_interface_components
ALTER TABLE `pm_user_role_has_interface_components` 
DROP FOREIGN KEY `pm_urhic_pm_ur_id_foreign`;

-- 2. Add the new restrictive constraint for pm_user_role_has_interface_components
ALTER TABLE `pm_user_role_has_interface_components` 
ADD CONSTRAINT `pm_urhic_pm_ur_id_foreign` 
FOREIGN KEY (`pm_user_role_id`) REFERENCES `pm_user_role`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_variation
ALTER TABLE `pm_variation` 
DROP FOREIGN KEY `pm_variation_created_by_foreign`;

-- 2. Add the new restrictive constraint for pm_variation
ALTER TABLE `pm_variation` 
ADD CONSTRAINT `pm_variation_created_by_foreign` 
FOREIGN KEY (`created_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_variation_value
ALTER TABLE `pm_variation_value` 
DROP FOREIGN KEY `pm_variation_value_created_by_foreign`;

-- 2. Add the new restrictive constraint for pm_variation_value
ALTER TABLE `pm_variation_value` 
ADD CONSTRAINT `pm_variation_value_created_by_foreign` 
FOREIGN KEY (`created_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_variation_value
ALTER TABLE `pm_variation_value` 
DROP FOREIGN KEY `pm_variation_value_pm_variation_id_foreign`;

-- 2. Add the new restrictive constraint for pm_variation_value
ALTER TABLE `pm_variation_value` 
ADD CONSTRAINT `pm_variation_value_pm_variation_id_foreign` 
FOREIGN KEY (`pm_variation_id`) REFERENCES `pm_variation`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for pm_variation_value
ALTER TABLE `pm_variation_value` 
DROP FOREIGN KEY `pm_variation_value_updated_by_foreign`;

-- 2. Add the new restrictive constraint for pm_variation_value
ALTER TABLE `pm_variation_value` 
ADD CONSTRAINT `pm_variation_value_updated_by_foreign` 
FOREIGN KEY (`updated_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for ad_daily_loads_has_product_items
ALTER TABLE `ad_daily_loads_has_product_items` 
DROP FOREIGN KEY `product_item_fk`;

-- 2. Add the new restrictive constraint for ad_daily_loads_has_product_items
ALTER TABLE `ad_daily_loads_has_product_items` 
ADD CONSTRAINT `product_item_fk` 
FOREIGN KEY (`product_item_id`) REFERENCES `pm_product_item`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for so_invoice
ALTER TABLE `so_invoice` 
DROP FOREIGN KEY `so_invoice_cm_customer_id_foreign`;

-- 2. Add the new restrictive constraint for so_invoice
ALTER TABLE `so_invoice` 
ADD CONSTRAINT `so_invoice_cm_customer_id_foreign` 
FOREIGN KEY (`cm_customer_id`) REFERENCES `cm_customer`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for so_invoice
ALTER TABLE `so_invoice` 
DROP FOREIGN KEY `so_invoice_created_by_foreign`;

-- 2. Add the new restrictive constraint for so_invoice
ALTER TABLE `so_invoice` 
ADD CONSTRAINT `so_invoice_created_by_foreign` 
FOREIGN KEY (`created_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for so_invoice_has_stock
ALTER TABLE `so_invoice_has_stock` 
DROP FOREIGN KEY `so_invoice_has_stock_created_by_foreign`;

-- 2. Add the new restrictive constraint for so_invoice_has_stock
ALTER TABLE `so_invoice_has_stock` 
ADD CONSTRAINT `so_invoice_has_stock_created_by_foreign` 
FOREIGN KEY (`created_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for so_invoice_has_stock
ALTER TABLE `so_invoice_has_stock` 
DROP FOREIGN KEY `so_invoice_has_stock_pm_product_item_id_foreign`;

-- 2. Add the new restrictive constraint for so_invoice_has_stock
ALTER TABLE `so_invoice_has_stock` 
ADD CONSTRAINT `so_invoice_has_stock_pm_product_item_id_foreign` 
FOREIGN KEY (`pm_product_item_id`) REFERENCES `pm_product_item`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for so_invoice_has_stock
ALTER TABLE `so_invoice_has_stock` 
DROP FOREIGN KEY `so_invoice_has_stock_so_invoice_id_foreign`;

-- 2. Add the new restrictive constraint for so_invoice_has_stock
ALTER TABLE `so_invoice_has_stock` 
ADD CONSTRAINT `so_invoice_has_stock_so_invoice_id_foreign` 
FOREIGN KEY (`so_invoice_id`) REFERENCES `so_invoice`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for so_invoice_has_stock
ALTER TABLE `so_invoice_has_stock` 
DROP FOREIGN KEY `so_invoice_has_stock_stm_branch_stock_id_foreign`;

-- 2. Add the new restrictive constraint for so_invoice_has_stock
ALTER TABLE `so_invoice_has_stock` 
ADD CONSTRAINT `so_invoice_has_stock_stm_branch_stock_id_foreign` 
FOREIGN KEY (`stm_branch_stock_id`) REFERENCES `stm_branch_stock`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for so_invoice_has_stock
ALTER TABLE `so_invoice_has_stock` 
DROP FOREIGN KEY `so_invoice_has_stock_um_branch_id_foreign`;

-- 2. Add the new restrictive constraint for so_invoice_has_stock
ALTER TABLE `so_invoice_has_stock` 
ADD CONSTRAINT `so_invoice_has_stock_um_branch_id_foreign` 
FOREIGN KEY (`um_branch_id`) REFERENCES `um_branch`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for so_invoice_has_stock
ALTER TABLE `so_invoice_has_stock` 
DROP FOREIGN KEY `so_invoice_has_stock_updated_by_foreign`;

-- 2. Add the new restrictive constraint for so_invoice_has_stock
ALTER TABLE `so_invoice_has_stock` 
ADD CONSTRAINT `so_invoice_has_stock_updated_by_foreign` 
FOREIGN KEY (`updated_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for so_invoice
ALTER TABLE `so_invoice` 
DROP FOREIGN KEY `so_invoice_um_branch_id_foreign`;

-- 2. Add the new restrictive constraint for so_invoice
ALTER TABLE `so_invoice` 
ADD CONSTRAINT `so_invoice_um_branch_id_foreign` 
FOREIGN KEY (`um_branch_id`) REFERENCES `um_branch`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for so_payments
ALTER TABLE `so_payments` 
DROP FOREIGN KEY `so_payments_created_by_foreign`;

-- 2. Add the new restrictive constraint for so_payments
ALTER TABLE `so_payments` 
ADD CONSTRAINT `so_payments_created_by_foreign` 
FOREIGN KEY (`created_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for so_payments
ALTER TABLE `so_payments` 
DROP FOREIGN KEY `so_payments_so_invoice_id_foreign`;

-- 2. Add the new restrictive constraint for so_payments
ALTER TABLE `so_payments` 
ADD CONSTRAINT `so_payments_so_invoice_id_foreign` 
FOREIGN KEY (`so_invoice_id`) REFERENCES `so_invoice`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for so_payments
ALTER TABLE `so_payments` 
DROP FOREIGN KEY `so_payments_updated_by_foreign`;

-- 2. Add the new restrictive constraint for so_payments
ALTER TABLE `so_payments` 
ADD CONSTRAINT `so_payments_updated_by_foreign` 
FOREIGN KEY (`updated_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_barcodes
ALTER TABLE `stm_barcodes` 
DROP FOREIGN KEY `stm_barcodes_ad_daily_load_id_foreign`;

-- 2. Add the new restrictive constraint for stm_barcodes
ALTER TABLE `stm_barcodes` 
ADD CONSTRAINT `stm_barcodes_ad_daily_load_id_foreign` 
FOREIGN KEY (`ad_daily_load_id`) REFERENCES `ad_daily_loads`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_barcodes
ALTER TABLE `stm_barcodes` 
DROP FOREIGN KEY `stm_barcodes_created_by_foreign`;

-- 2. Add the new restrictive constraint for stm_barcodes
ALTER TABLE `stm_barcodes` 
ADD CONSTRAINT `stm_barcodes_created_by_foreign` 
FOREIGN KEY (`created_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_barcodes_history
ALTER TABLE `stm_barcodes_history` 
DROP FOREIGN KEY `stm_barcodes_history_barcode_id_foreign`;

-- 2. Add the new restrictive constraint for stm_barcodes_history
ALTER TABLE `stm_barcodes_history` 
ADD CONSTRAINT `stm_barcodes_history_barcode_id_foreign` 
FOREIGN KEY (`barcode_id`) REFERENCES `stm_barcodes`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_barcodes_history
ALTER TABLE `stm_barcodes_history` 
DROP FOREIGN KEY `stm_barcodes_history_created_by_foreign`;

-- 2. Add the new restrictive constraint for stm_barcodes_history
ALTER TABLE `stm_barcodes_history` 
ADD CONSTRAINT `stm_barcodes_history_created_by_foreign` 
FOREIGN KEY (`created_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_barcodes
ALTER TABLE `stm_barcodes` 
DROP FOREIGN KEY `stm_barcodes_pm_product_item_id_foreign`;

-- 2. Add the new restrictive constraint for stm_barcodes
ALTER TABLE `stm_barcodes` 
ADD CONSTRAINT `stm_barcodes_pm_product_item_id_foreign` 
FOREIGN KEY (`pm_product_item_id`) REFERENCES `pm_product_item`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_barcodes
ALTER TABLE `stm_barcodes` 
DROP FOREIGN KEY `stm_barcodes_stm_stock_id_foreign`;

-- 2. Add the new restrictive constraint for stm_barcodes
ALTER TABLE `stm_barcodes` 
ADD CONSTRAINT `stm_barcodes_stm_stock_id_foreign` 
FOREIGN KEY (`stm_stock_id`) REFERENCES `stm_stock`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_barcodes
ALTER TABLE `stm_barcodes` 
DROP FOREIGN KEY `stm_barcodes_stm_stock_order_request_id_foreign`;

-- 2. Add the new restrictive constraint for stm_barcodes
ALTER TABLE `stm_barcodes` 
ADD CONSTRAINT `stm_barcodes_stm_stock_order_request_id_foreign` 
FOREIGN KEY (`stm_stock_order_request_id`) REFERENCES `stm_stock_order_request`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_barcodes
ALTER TABLE `stm_barcodes` 
DROP FOREIGN KEY `stm_barcodes_um_branch_id_foreign`;

-- 2. Add the new restrictive constraint for stm_barcodes
ALTER TABLE `stm_barcodes` 
ADD CONSTRAINT `stm_barcodes_um_branch_id_foreign` 
FOREIGN KEY (`um_branch_id`) REFERENCES `um_branch`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_branch_stock
ALTER TABLE `stm_branch_stock` 
DROP FOREIGN KEY `stm_branch_stock_created_by_foreign`;

-- 2. Add the new restrictive constraint for stm_branch_stock
ALTER TABLE `stm_branch_stock` 
ADD CONSTRAINT `stm_branch_stock_created_by_foreign` 
FOREIGN KEY (`created_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_branch_stock
ALTER TABLE `stm_branch_stock` 
DROP FOREIGN KEY `stm_branch_stock_pln_department_id_foreign`;

-- 2. Add the new restrictive constraint for stm_branch_stock
ALTER TABLE `stm_branch_stock` 
ADD CONSTRAINT `stm_branch_stock_pln_department_id_foreign` 
FOREIGN KEY (`pln_department_id`) REFERENCES `pln_departments`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_branch_stock
ALTER TABLE `stm_branch_stock` 
DROP FOREIGN KEY `stm_branch_stock_pm_product_item_id_foreign`;

-- 2. Add the new restrictive constraint for stm_branch_stock
ALTER TABLE `stm_branch_stock` 
ADD CONSTRAINT `stm_branch_stock_pm_product_item_id_foreign` 
FOREIGN KEY (`pm_product_item_id`) REFERENCES `pm_product_item`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_branch_stock
ALTER TABLE `stm_branch_stock` 
DROP FOREIGN KEY `stm_branch_stock_stm_stock_id_foreign`;

-- 2. Add the new restrictive constraint for stm_branch_stock
ALTER TABLE `stm_branch_stock` 
ADD CONSTRAINT `stm_branch_stock_stm_stock_id_foreign` 
FOREIGN KEY (`stm_stock_id`) REFERENCES `stm_stock`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_branch_stock
ALTER TABLE `stm_branch_stock` 
DROP FOREIGN KEY `stm_branch_stock_stm_stock_transfer_id_foreign`;

-- 2. Add the new restrictive constraint for stm_branch_stock
ALTER TABLE `stm_branch_stock` 
ADD CONSTRAINT `stm_branch_stock_stm_stock_transfer_id_foreign` 
FOREIGN KEY (`stm_stock_transfer_id`) REFERENCES `stm_stock_transfer`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_branch_stock
ALTER TABLE `stm_branch_stock` 
DROP FOREIGN KEY `stm_branch_stock_um_branch_id_foreign`;

-- 2. Add the new restrictive constraint for stm_branch_stock
ALTER TABLE `stm_branch_stock` 
ADD CONSTRAINT `stm_branch_stock_um_branch_id_foreign` 
FOREIGN KEY (`um_branch_id`) REFERENCES `um_branch`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_branch_stock
ALTER TABLE `stm_branch_stock` 
DROP FOREIGN KEY `stm_branch_stock_updated_by_foreign`;

-- 2. Add the new restrictive constraint for stm_branch_stock
ALTER TABLE `stm_branch_stock` 
ADD CONSTRAINT `stm_branch_stock_updated_by_foreign` 
FOREIGN KEY (`updated_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_grn
ALTER TABLE `stm_grn` 
DROP FOREIGN KEY `stm_grn_purchase_order_id_foreign`;

-- 2. Add the new restrictive constraint for stm_grn
ALTER TABLE `stm_grn` 
ADD CONSTRAINT `stm_grn_purchase_order_id_foreign` 
FOREIGN KEY (`purchase_order_id`) REFERENCES `stm_purchase_order`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_order_requests
ALTER TABLE `stm_order_requests` 
DROP FOREIGN KEY `stm_order_requests_branch_id_foreign`;

-- 2. Add the new restrictive constraint for stm_order_requests
ALTER TABLE `stm_order_requests` 
ADD CONSTRAINT `stm_order_requests_branch_id_foreign` 
FOREIGN KEY (`branch_id`) REFERENCES `um_branch`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_order_requests_has_product
ALTER TABLE `stm_order_requests_has_product` 
DROP FOREIGN KEY `stm_order_requests_has_product_pm_product_item_id_foreign`;

-- 2. Add the new restrictive constraint for stm_order_requests_has_product
ALTER TABLE `stm_order_requests_has_product` 
ADD CONSTRAINT `stm_order_requests_has_product_pm_product_item_id_foreign` 
FOREIGN KEY (`pm_product_item_id`) REFERENCES `pm_product_item`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_order_requests_has_product
ALTER TABLE `stm_order_requests_has_product` 
DROP FOREIGN KEY `stm_order_requests_has_product_stm_order_request_id_foreign`;

-- 2. Add the new restrictive constraint for stm_order_requests_has_product
ALTER TABLE `stm_order_requests_has_product` 
ADD CONSTRAINT `stm_order_requests_has_product_stm_order_request_id_foreign` 
FOREIGN KEY (`stm_order_request_id`) REFERENCES `stm_order_requests`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_order_requests
ALTER TABLE `stm_order_requests` 
DROP FOREIGN KEY `stm_order_requests_req_from_branch_id_foreign`;

-- 2. Add the new restrictive constraint for stm_order_requests
ALTER TABLE `stm_order_requests` 
ADD CONSTRAINT `stm_order_requests_req_from_branch_id_foreign` 
FOREIGN KEY (`req_from_branch_id`) REFERENCES `um_branch`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_order_request_has_payments
ALTER TABLE `stm_order_request_has_payments` 
DROP FOREIGN KEY `stm_order_request_has_payments_stm_order_request_id_foreign`;

-- 2. Add the new restrictive constraint for stm_order_request_has_payments
ALTER TABLE `stm_order_request_has_payments` 
ADD CONSTRAINT `stm_order_request_has_payments_stm_order_request_id_foreign` 
FOREIGN KEY (`stm_order_request_id`) REFERENCES `stm_order_requests`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_order_request_history
ALTER TABLE `stm_order_request_history` 
DROP FOREIGN KEY `stm_order_request_history_order_request_id_foreign`;

-- 2. Add the new restrictive constraint for stm_order_request_history
ALTER TABLE `stm_order_request_history` 
ADD CONSTRAINT `stm_order_request_history_order_request_id_foreign` 
FOREIGN KEY (`order_request_id`) REFERENCES `stm_order_requests`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_purchase_order_audit
ALTER TABLE `stm_purchase_order_audit` 
DROP FOREIGN KEY `stm_purchase_order_audit_purchase_order_id_foreign`;

-- 2. Add the new restrictive constraint for stm_purchase_order_audit
ALTER TABLE `stm_purchase_order_audit` 
ADD CONSTRAINT `stm_purchase_order_audit_purchase_order_id_foreign` 
FOREIGN KEY (`purchase_order_id`) REFERENCES `stm_purchase_order`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_purchase_order_has_product_items
ALTER TABLE `stm_purchase_order_has_product_items` 
DROP FOREIGN KEY `stm_purchase_order_has_product_items_product_item_id_foreign`;

-- 2. Add the new restrictive constraint for stm_purchase_order_has_product_items
ALTER TABLE `stm_purchase_order_has_product_items` 
ADD CONSTRAINT `stm_purchase_order_has_product_items_product_item_id_foreign` 
FOREIGN KEY (`product_item_id`) REFERENCES `pm_product_item`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_purchase_order_has_product_items
ALTER TABLE `stm_purchase_order_has_product_items` 
DROP FOREIGN KEY `stm_purchase_order_has_product_items_purchase_order_id_foreign`;

-- 2. Add the new restrictive constraint for stm_purchase_order_has_product_items
ALTER TABLE `stm_purchase_order_has_product_items` 
ADD CONSTRAINT `stm_purchase_order_has_product_items_purchase_order_id_foreign` 
FOREIGN KEY (`purchase_order_id`) REFERENCES `stm_purchase_order`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_purchase_order
ALTER TABLE `stm_purchase_order` 
DROP FOREIGN KEY `stm_purchase_order_supplier_id_foreign`;

-- 2. Add the new restrictive constraint for stm_purchase_order
ALTER TABLE `stm_purchase_order` 
ADD CONSTRAINT `stm_purchase_order_supplier_id_foreign` 
FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_quotation
ALTER TABLE `stm_quotation` 
DROP FOREIGN KEY `stm_quotation_customer_id_foreign`;

-- 2. Add the new restrictive constraint for stm_quotation
ALTER TABLE `stm_quotation` 
ADD CONSTRAINT `stm_quotation_customer_id_foreign` 
FOREIGN KEY (`customer_id`) REFERENCES `cm_customer`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_quotation_has_products
ALTER TABLE `stm_quotation_has_products` 
DROP FOREIGN KEY `stm_quotation_has_products_stm_quotation_id_foreign`;

-- 2. Add the new restrictive constraint for stm_quotation_has_products
ALTER TABLE `stm_quotation_has_products` 
ADD CONSTRAINT `stm_quotation_has_products_stm_quotation_id_foreign` 
FOREIGN KEY (`stm_quotation_id`) REFERENCES `stm_quotation`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock_in
ALTER TABLE `stm_stock_in` 
DROP FOREIGN KEY `stm_stock_in_pm_product_item_id_foreign`;

-- 2. Add the new restrictive constraint for stm_stock_in
ALTER TABLE `stm_stock_in` 
ADD CONSTRAINT `stm_stock_in_pm_product_item_id_foreign` 
FOREIGN KEY (`pm_product_item_id`) REFERENCES `pm_product_item`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock_in
ALTER TABLE `stm_stock_in` 
DROP FOREIGN KEY `stm_stock_in_stm_grn_id_foreign`;

-- 2. Add the new restrictive constraint for stm_stock_in
ALTER TABLE `stm_stock_in` 
ADD CONSTRAINT `stm_stock_in_stm_grn_id_foreign` 
FOREIGN KEY (`stm_grn_id`) REFERENCES `stm_grn`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock_order_request
ALTER TABLE `stm_stock_order_request` 
DROP FOREIGN KEY `stm_stock_order_request_created_by_foreign`;

-- 2. Add the new restrictive constraint for stm_stock_order_request
ALTER TABLE `stm_stock_order_request` 
ADD CONSTRAINT `stm_stock_order_request_created_by_foreign` 
FOREIGN KEY (`created_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock_order_request_history
ALTER TABLE `stm_stock_order_request_history` 
DROP FOREIGN KEY `stm_stock_order_request_history_created_by_foreign`;

-- 2. Add the new restrictive constraint for stm_stock_order_request_history
ALTER TABLE `stm_stock_order_request_history` 
ADD CONSTRAINT `stm_stock_order_request_history_created_by_foreign` 
FOREIGN KEY (`created_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock_order_request_history
ALTER TABLE `stm_stock_order_request_history` 
DROP FOREIGN KEY `stm_stock_order_request_history_order_request_id_foreign`;

-- 2. Add the new restrictive constraint for stm_stock_order_request_history
ALTER TABLE `stm_stock_order_request_history` 
ADD CONSTRAINT `stm_stock_order_request_history_order_request_id_foreign` 
FOREIGN KEY (`order_request_id`) REFERENCES `stm_stock_order_request`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock_order_request
ALTER TABLE `stm_stock_order_request` 
DROP FOREIGN KEY `stm_stock_order_request_pln_department_id_foreign`;

-- 2. Add the new restrictive constraint for stm_stock_order_request
ALTER TABLE `stm_stock_order_request` 
ADD CONSTRAINT `stm_stock_order_request_pln_department_id_foreign` 
FOREIGN KEY (`pln_department_id`) REFERENCES `pln_departments`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock_order_request
ALTER TABLE `stm_stock_order_request` 
DROP FOREIGN KEY `stm_stock_order_request_requesting_from_foreign`;

-- 2. Add the new restrictive constraint for stm_stock_order_request
ALTER TABLE `stm_stock_order_request` 
ADD CONSTRAINT `stm_stock_order_request_requesting_from_foreign` 
FOREIGN KEY (`req_from_branch_id`) REFERENCES `um_branch`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock_order_request
ALTER TABLE `stm_stock_order_request` 
DROP FOREIGN KEY `stm_stock_order_request_req_from_department_id_foreign`;

-- 2. Add the new restrictive constraint for stm_stock_order_request
ALTER TABLE `stm_stock_order_request` 
ADD CONSTRAINT `stm_stock_order_request_req_from_department_id_foreign` 
FOREIGN KEY (`req_from_department_id`) REFERENCES `pln_departments`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock_order_request
ALTER TABLE `stm_stock_order_request` 
DROP FOREIGN KEY `stm_stock_order_request_um_branch_id_foreign`;

-- 2. Add the new restrictive constraint for stm_stock_order_request
ALTER TABLE `stm_stock_order_request` 
ADD CONSTRAINT `stm_stock_order_request_um_branch_id_foreign` 
FOREIGN KEY (`um_branch_id`) REFERENCES `um_branch`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock_order_request
ALTER TABLE `stm_stock_order_request` 
DROP FOREIGN KEY `stm_stock_order_request_updated_by_foreign`;

-- 2. Add the new restrictive constraint for stm_stock_order_request
ALTER TABLE `stm_stock_order_request` 
ADD CONSTRAINT `stm_stock_order_request_updated_by_foreign` 
FOREIGN KEY (`updated_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock
ALTER TABLE `stm_stock` 
DROP FOREIGN KEY `stm_stock_pm_product_item_id_foreign`;

-- 2. Add the new restrictive constraint for stm_stock
ALTER TABLE `stm_stock` 
ADD CONSTRAINT `stm_stock_pm_product_item_id_foreign` 
FOREIGN KEY (`pm_product_item_id`) REFERENCES `pm_product_item`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock
ALTER TABLE `stm_stock` 
DROP FOREIGN KEY `stm_stock_stm_stock_in_id_foreign`;

-- 2. Add the new restrictive constraint for stm_stock
ALTER TABLE `stm_stock` 
ADD CONSTRAINT `stm_stock_stm_stock_in_id_foreign` 
FOREIGN KEY (`stm_stock_in_id`) REFERENCES `stm_stock_in`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock_transfer
ALTER TABLE `stm_stock_transfer` 
DROP FOREIGN KEY `stm_stock_transfer_approved_by_foreign`;

-- 2. Add the new restrictive constraint for stm_stock_transfer
ALTER TABLE `stm_stock_transfer` 
ADD CONSTRAINT `stm_stock_transfer_approved_by_foreign` 
FOREIGN KEY (`approved_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock_transfer
ALTER TABLE `stm_stock_transfer` 
DROP FOREIGN KEY `stm_stock_transfer_branch_stock_id_foreign`;

-- 2. Add the new restrictive constraint for stm_stock_transfer
ALTER TABLE `stm_stock_transfer` 
ADD CONSTRAINT `stm_stock_transfer_branch_stock_id_foreign` 
FOREIGN KEY (`branch_stock_id`) REFERENCES `stm_branch_stock`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock_transfer
ALTER TABLE `stm_stock_transfer` 
DROP FOREIGN KEY `stm_stock_transfer_dispatched_by_foreign`;

-- 2. Add the new restrictive constraint for stm_stock_transfer
ALTER TABLE `stm_stock_transfer` 
ADD CONSTRAINT `stm_stock_transfer_dispatched_by_foreign` 
FOREIGN KEY (`dispatched_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock_transfer
ALTER TABLE `stm_stock_transfer` 
DROP FOREIGN KEY `stm_stock_transfer_pm_product_item_id_foreign`;

-- 2. Add the new restrictive constraint for stm_stock_transfer
ALTER TABLE `stm_stock_transfer` 
ADD CONSTRAINT `stm_stock_transfer_pm_product_item_id_foreign` 
FOREIGN KEY (`pm_product_item_id`) REFERENCES `pm_product_item`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock_transfer
ALTER TABLE `stm_stock_transfer` 
DROP FOREIGN KEY `stm_stock_transfer_received_by_foreign`;

-- 2. Add the new restrictive constraint for stm_stock_transfer
ALTER TABLE `stm_stock_transfer` 
ADD CONSTRAINT `stm_stock_transfer_received_by_foreign` 
FOREIGN KEY (`received_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock_transfer
ALTER TABLE `stm_stock_transfer` 
DROP FOREIGN KEY `stm_stock_transfer_stm_order_request_id_foreign`;

-- 2. Add the new restrictive constraint for stm_stock_transfer
ALTER TABLE `stm_stock_transfer` 
ADD CONSTRAINT `stm_stock_transfer_stm_order_request_id_foreign` 
FOREIGN KEY (`stm_order_request_id`) REFERENCES `stm_order_requests`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock_transfer
ALTER TABLE `stm_stock_transfer` 
DROP FOREIGN KEY `stm_stock_transfer_stm_stock_id_foreign`;

-- 2. Add the new restrictive constraint for stm_stock_transfer
ALTER TABLE `stm_stock_transfer` 
ADD CONSTRAINT `stm_stock_transfer_stm_stock_id_foreign` 
FOREIGN KEY (`stm_stock_id`) REFERENCES `stm_stock`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for stm_stock_transfer
ALTER TABLE `stm_stock_transfer` 
DROP FOREIGN KEY `stm_stock_transfer_stm_stock_order_request_id_foreign`;

-- 2. Add the new restrictive constraint for stm_stock_transfer
ALTER TABLE `stm_stock_transfer` 
ADD CONSTRAINT `stm_stock_transfer_stm_stock_order_request_id_foreign` 
FOREIGN KEY (`stm_stock_order_request_id`) REFERENCES `stm_stock_order_request`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for supplier_contacts
ALTER TABLE `supplier_contacts` 
DROP FOREIGN KEY `supplier_contacts_supplier_id_foreign`;

-- 2. Add the new restrictive constraint for supplier_contacts
ALTER TABLE `supplier_contacts` 
ADD CONSTRAINT `supplier_contacts_supplier_id_foreign` 
FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for supplier_product_items
ALTER TABLE `supplier_product_items` 
DROP FOREIGN KEY `supplier_product_items_product_item_id_foreign`;

-- 2. Add the new restrictive constraint for supplier_product_items
ALTER TABLE `supplier_product_items` 
ADD CONSTRAINT `supplier_product_items_product_item_id_foreign` 
FOREIGN KEY (`product_item_id`) REFERENCES `pm_product_item`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for supplier_product_items
ALTER TABLE `supplier_product_items` 
DROP FOREIGN KEY `supplier_product_items_supplier_id_foreign`;

-- 2. Add the new restrictive constraint for supplier_product_items
ALTER TABLE `supplier_product_items` 
ADD CONSTRAINT `supplier_product_items_supplier_id_foreign` 
FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for um_branch
ALTER TABLE `um_branch` 
DROP FOREIGN KEY `um_branch_created_by_foreign`;

-- 2. Add the new restrictive constraint for um_branch
ALTER TABLE `um_branch` 
ADD CONSTRAINT `um_branch_created_by_foreign` 
FOREIGN KEY (`created_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for um_branch_has_resources
ALTER TABLE `um_branch_has_resources` 
DROP FOREIGN KEY `um_branch_has_resources_created_by_foreign`;

-- 2. Add the new restrictive constraint for um_branch_has_resources
ALTER TABLE `um_branch_has_resources` 
ADD CONSTRAINT `um_branch_has_resources_created_by_foreign` 
FOREIGN KEY (`created_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for um_branch_has_resources
ALTER TABLE `um_branch_has_resources` 
DROP FOREIGN KEY `um_branch_has_resources_pln_department_id_foreign`;

-- 2. Add the new restrictive constraint for um_branch_has_resources
ALTER TABLE `um_branch_has_resources` 
ADD CONSTRAINT `um_branch_has_resources_pln_department_id_foreign` 
FOREIGN KEY (`pln_department_id`) REFERENCES `pln_departments`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for um_branch_has_resources
ALTER TABLE `um_branch_has_resources` 
DROP FOREIGN KEY `um_branch_has_resources_pln_resource_id_foreign`;

-- 2. Add the new restrictive constraint for um_branch_has_resources
ALTER TABLE `um_branch_has_resources` 
ADD CONSTRAINT `um_branch_has_resources_pln_resource_id_foreign` 
FOREIGN KEY (`pln_resource_id`) REFERENCES `pln_resources`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for um_branch_has_resources
ALTER TABLE `um_branch_has_resources` 
DROP FOREIGN KEY `um_branch_has_resources_um_branch_id_foreign`;

-- 2. Add the new restrictive constraint for um_branch_has_resources
ALTER TABLE `um_branch_has_resources` 
ADD CONSTRAINT `um_branch_has_resources_um_branch_id_foreign` 
FOREIGN KEY (`um_branch_id`) REFERENCES `um_branch`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for um_branch_has_resources
ALTER TABLE `um_branch_has_resources` 
DROP FOREIGN KEY `um_branch_has_resources_updated_by_foreign`;

-- 2. Add the new restrictive constraint for um_branch_has_resources
ALTER TABLE `um_branch_has_resources` 
ADD CONSTRAINT `um_branch_has_resources_updated_by_foreign` 
FOREIGN KEY (`updated_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for um_branch
ALTER TABLE `um_branch` 
DROP FOREIGN KEY `um_branch_um_branch_type_id_foreign`;

-- 2. Add the new restrictive constraint for um_branch
ALTER TABLE `um_branch` 
ADD CONSTRAINT `um_branch_um_branch_type_id_foreign` 
FOREIGN KEY (`um_branch_type_id`) REFERENCES `um_branch_type`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for um_branch
ALTER TABLE `um_branch` 
DROP FOREIGN KEY `um_branch_updated_by_foreign`;

-- 2. Add the new restrictive constraint for um_branch
ALTER TABLE `um_branch` 
ADD CONSTRAINT `um_branch_updated_by_foreign` 
FOREIGN KEY (`updated_by`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for um_user_has_interface_components
ALTER TABLE `um_user_has_interface_components` 
DROP FOREIGN KEY `um_uhic_pm_interface_components_id_foreign`;

-- 2. Add the new restrictive constraint for um_user_has_interface_components
ALTER TABLE `um_user_has_interface_components` 
ADD CONSTRAINT `um_uhic_pm_interface_components_id_foreign` 
FOREIGN KEY (`pm_interface_components_id`) REFERENCES `pm_interface_components`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for um_user_has_interface_components
ALTER TABLE `um_user_has_interface_components` 
DROP FOREIGN KEY `um_uhic_um_user_id_foreign`;

-- 2. Add the new restrictive constraint for um_user_has_interface_components
ALTER TABLE `um_user_has_interface_components` 
ADD CONSTRAINT `um_uhic_um_user_id_foreign` 
FOREIGN KEY (`um_user_id`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for um_user
ALTER TABLE `um_user` 
DROP FOREIGN KEY `um_user_current_branch_id_foreign`;

-- 2. Add the new restrictive constraint for um_user
ALTER TABLE `um_user` 
ADD CONSTRAINT `um_user_current_branch_id_foreign` 
FOREIGN KEY (`current_branch_id`) REFERENCES `um_branch`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for um_user_has_interface_topic
ALTER TABLE `um_user_has_interface_topic` 
DROP FOREIGN KEY `um_user_has_interface_topic_pm_interface_topic_id_foreign`;

-- 2. Add the new restrictive constraint for um_user_has_interface_topic
ALTER TABLE `um_user_has_interface_topic` 
ADD CONSTRAINT `um_user_has_interface_topic_pm_interface_topic_id_foreign` 
FOREIGN KEY (`pm_interface_topic_id`) REFERENCES `pm_interface_topic`(`id`) 
ON DELETE RESTRICT;

-- 1. Remove the existing cascading constraint for um_user_has_interface_topic
ALTER TABLE `um_user_has_interface_topic` 
DROP FOREIGN KEY `um_user_has_interface_topic_um_user_id_foreign`;

-- 2. Add the new restrictive constraint for um_user_has_interface_topic
ALTER TABLE `um_user_has_interface_topic` 
ADD CONSTRAINT `um_user_has_interface_topic_um_user_id_foreign` 
FOREIGN KEY (`um_user_id`) REFERENCES `um_user`(`id`) 
ON DELETE RESTRICT;

