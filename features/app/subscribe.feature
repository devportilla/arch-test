Feature: Subscribe User
  In order to subscribe a user to our email system
  As a developer
  I need to do this on a cli based environment

  Scenario Outline: Command with correct email
    When I run 'user:subscribe' command with email '<sample_mail>'
    Then I should see
    """
    User with id id and email <sample_mail> created
    """
    Then The command exit code should be 0

    Examples:
      | sample_mail                                 |
      | test@ulabox.com                             |
      | thisisaverylargeemail@reallyisverylarge.com |

  Scenario: The subscriber already exists
    Given there are subscribers:
      | id | email                         |
      | 1  | alreadysubscribed@already.com |
    When I run 'user:subscribe' command with email 'alreadysubscribed@already.com'
    Then I should see
    """
    The subscriber <alreadysubscribed@already.com> already exists
    """
    Then The command exit code should be 1

  Scenario: The subscriber email is not valid
    When I run 'user:subscribe' command with email 'invalidemail'
    Then I should see
    """
    Email address is not valid
    """
    Then The command exit code should be 2
