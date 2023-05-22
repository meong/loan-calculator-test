# loan-calculator

## initalize
```shell
curl -s "https://laravel.build/loan-calculator" | bash
cd loan-calculator
sail up
```

- [x] 1. Create a web application using Laravel that allows users to input the following details on a form:
   ● Loan amount (principal)
   ● Annual interest rate (in percentage)
   ● Loan term (in years)
   ● Monthly fixed extra payment (optional)

- [x] 2. Validate the input values to ensure they are valid and provide appropriate error messages for invalid values, such as negative loan amount, interest rate, or loan term.
- [x] 3. Implement the logic to calculate the monthly payment amount using the following formula:
   ● Monthly interest rate = (Annual interest rate / 12) / 100
   ● Number of months = Loan term * 12
   ● Monthly payment = (Loan amount * Monthly interest rate) / (1 - (1 + Monthly
   interest rate)^(-Number of months))
- [x] 4. Generate an amortization schedule that shows the monthly payment breakdown, including the principal and interest components, for the entire loan term. Store the amortization schedule data in a database table, such as "loan_amortization_schedule", with columns for month number, starting balance, monthly payment, principal component, interest component, and ending balance.
- [ ] 5. Implement the logic to allow for fixed extra repayments made by the borrower. If the borrower makes extra repayments, the monthly payment should remain the same, but the extra repayment amount should be deducted from the remaining loan balance. Update the amortization schedule in the database to reflect the new loan balance and the shortened loan term.
- [x] 6. Generate a schedule that shows the recalculated, shortened loans due to extra payments made by the borrower. Store the updated schedule data in a separate database table, such as "extra_repayment_schedule", with columns for month number, starting balance, monthly payment (unchanged), principal component (unchanged), interest component (unchanged), extra repayment made (if any), ending balance after extra repayment, and remaining loan term after extra repayment.
    - // TODO : 남은 대출 기간 정보 획득 필요
    - // TODO : 마지막 달 스케쥴에 금액이 오버하는 경우 0원에 맞추어 이자율과 함께 계산 필요
- [ ] 7. Display a header in the generated tables that shows the loan setup details, including loan amount, annual interest rate, loan term, and the effective interest rate. The effective interest rate should take into account any extra repayments made by the borrower, and should be calculated based on the remaining loan balance after each repayment.
- [ ] 8. Implement appropriate routing, controllers, and views in Laravel to display the amortization schedule and the schedule with recalculated, shortened loans to the user, including the header with loan setup details and effective interest rate.
- [ ] 9. Test the web application with different input values to ensure its correctness and accuracy in calculating the monthly payment, generating the amortization schedule, updating the loan term and loan balance due to extra payments, and displaying the header with loan setup details and effective interest rate.
- [ ] 10. Provide a sample input and output to demonstrate the functionality of the web application, including the amortization schedule and the schedule with recalculated, shortened loans, along with the header showing loan setup details and effective interest rate.
