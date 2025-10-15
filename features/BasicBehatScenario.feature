# language: en
Feature: Behat is working

    Scenario: Behat works
    Given chatbot-php is installed
    When I run Behat
    Then This Scenario success
