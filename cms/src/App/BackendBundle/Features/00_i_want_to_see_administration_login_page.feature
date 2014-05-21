Feature: Admin User would like to see administration login page

    Scenario: I would like to see administration login page
        Given I am on "/admin/login"
        And I should see "Panel administracyjny"
        And I should see "Nazwa użytkownika"
        And I should see "E-mail"