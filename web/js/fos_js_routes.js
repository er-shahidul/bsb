fos.Router.setData({"base_url":"","routes":{"ex_cervice_man_personal_no_auto_complete":{"tokens":[["text","\/personnel\/ex-serviceman\/ex_cervice_man_personal_no_auto_complete"]],"defaults":[],"requirements":[],"hosttokens":[]},"personnel_upazila_lookup":{"tokens":[["text","\/upazila"],["variable","\/","[^\/]++","district"],["text","\/personnel\/rest\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"personnel_service_child_lookup":{"tokens":[["variable","\/","[^\/]++","service"],["text","\/personnel\/rest\/\/service-child-lookup"]],"defaults":[],"requirements":[],"hosttokens":[]},"civilian_personal_no_auto_complete":{"tokens":[["text","\/personnel\/serving-civilian\/civilian_personal_no_auto_complete"]],"defaults":[],"requirements":[],"hosttokens":[]},"personal_no_auto_complete":{"tokens":[["text","\/personnel\/serving-military\/personal_no_auto_complete"]],"defaults":[],"requirements":[],"hosttokens":[]},"account_integration_edit":{"tokens":[["variable","\/","[^\/]++","fundTypeId"],["variable","\/","[^\/]++","id"],["text","\/accounts\/integration\/edit"]],"defaults":{"fundTypeId":null},"requirements":[],"hosttokens":[]},"account_report_from_to_data":{"tokens":[["variable","\/","[^\/]++","type"],["variable","\/","[^\/]++","fundType"],["text","\/accounts\/report\/from-to-data"]],"defaults":[],"requirements":[],"hosttokens":[]},"notification_sent_view":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/daily-correspondence\/show"]],"defaults":[],"requirements":[],"hosttokens":[]},"notification_view":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/daily-correspondence\/view"]],"defaults":[],"requirements":[],"hosttokens":[]},"homepage":{"tokens":[["text","\/"]],"defaults":[],"requirements":[],"hosttokens":[]},"master_data_dashboard":{"tokens":[["text","\/master-data"]],"defaults":[],"requirements":[],"hosttokens":[]},"app_file_download":{"tokens":[["variable","\/","[^\/]++","fileName"],["variable","\/","[^\/]++","id"],["text","\/attachment\/download"]],"defaults":[],"requirements":[],"hosttokens":[]},"office_auto_complete":{"tokens":[["text","\/office\/office_auto_complete"]],"defaults":[],"requirements":[],"hosttokens":[]},"groups_home":{"tokens":[["text","\/groups"]],"defaults":[],"requirements":[],"hosttokens":[]},"group_update":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/group\/update"]],"defaults":[],"requirements":[],"hosttokens":[]},"group_delete":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/group\/delete"]],"defaults":[],"requirements":[],"hosttokens":[]},"users_home":{"tokens":[["text","\/users"]],"defaults":[],"requirements":[],"hosttokens":[]},"user_update":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/user\/update"]],"defaults":[],"requirements":[],"hosttokens":[]},"user_update_password":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/user\/update\/password"]],"defaults":[],"requirements":[],"hosttokens":[]},"user_enabled":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/user\/enabled"]],"defaults":[],"requirements":[],"hosttokens":[]},"user_details":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/user\/details"]],"defaults":[],"requirements":[],"hosttokens":[]},"user_delete":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/user\/delete"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_fund_request_list":{"tokens":[["text","\/budget\/additional-budget-demand\/list"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_fund_request_update":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/additional-budget-demand\/update"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_fund_request_view":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/additional-budget-demand\/view"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_head_stats":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/additional-budget-demand\/stats"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_fund_request_allocation":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/additional-budget-demand\/allocation"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_allocation":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/allocation\/update"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_allocation_view":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/allocation\/view"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_amendment_list":{"tokens":[["text","\/budget\/amendment\/list"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_amendment_update":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/amendment\/update"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_amendment_view":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/amendment\/view"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_amendment_sanction_update":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/amendment\/update\/sanction"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_amendment_sanction_view":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/amendment\/view\/sanction"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_summary_list":{"tokens":[["text","\/budget\/compilation\/list"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_summary_update":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/compilation\/update"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_summary_view":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/compilation\/view"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_distribution":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/distribution\/update"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_distribution_view":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/distribution\/view"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_distribution_expense_summary":{"tokens":[["variable","\/","[^\/]++","officeId"],["variable","\/","[^\/]++","headId"],["variable","\/","[^\/]++","id"],["text","\/budget\/distribution\/expense-summary"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_expense_list":{"tokens":[["text","\/budget\/expense\/list"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_expense_update":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/expense\/update"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_expense_view":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/expense\/view"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_expense_summary_of_budget_head":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/expense\/expense-summary"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_expense_bill_sanction_list":{"tokens":[["text","\/budget\/expense\/sanction\/list"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_expense_bill_sanction_update":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/expense\/sanction\/update"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_expense_bill_sanction_view":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/expense\/sanction\/view"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_income_list":{"tokens":[["text","\/budget\/income\/list"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_income_update":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/income\/update"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_income_view":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/income\/view"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_income_amendment_list":{"tokens":[["text","\/budget\/budget-income-amendment\/list"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_income_amendment_update":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/budget-income-amendment\/update"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_income_amendment_view":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/budget-income-amendment\/view"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_income_summary_list":{"tokens":[["text","\/budget\/budget-income-preparation\/list"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_income_summary_update":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/budget-income-preparation\/update"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_income_summary_view":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/budget-income-preparation\/view"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_list":{"tokens":[["text","\/budget\/request\/list"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_update":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/request\/update"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_view":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/request\/view"]],"defaults":[],"requirements":[],"hosttokens":[]},"budget_requested_budget_list":{"tokens":[["text","\/budget\/request\/requested_budgets"]],"defaults":[],"requirements":[],"hosttokens":[]},"pre_budget_summary_list":{"tokens":[["text","\/budget\/pre-budget\/list"]],"defaults":[],"requirements":[],"hosttokens":[]},"pre_budget_summary_update":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/pre-budget\/update"]],"defaults":[],"requirements":[],"hosttokens":[]},"pre_budget_summary_view":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/pre-budget\/view"]],"defaults":[],"requirements":[],"hosttokens":[]},"pre_budget_income_summary_list":{"tokens":[["text","\/budget\/pre-budget-income\/list"]],"defaults":[],"requirements":[],"hosttokens":[]},"pre_budget_income_summary_update":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/pre-budget-income\/update"]],"defaults":[],"requirements":[],"hosttokens":[]},"pre_budget_income_summary_view":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/budget\/pre-budget-income\/view"]],"defaults":[],"requirements":[],"hosttokens":[]}},"prefix":"","host":"localhost","scheme":"http"});