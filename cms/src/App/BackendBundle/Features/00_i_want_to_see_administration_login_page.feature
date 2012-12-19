Feature: Admin User would like to see administration login page

    Scenario: I would like to see administration login page
        Given I am on "/admin" 
        And I should see "Content Management System"
        And I should see "Nazwa użytkownika"
        And I should see "Hasło"