---
title: ProcessSaveSurveyAnswer
layout: layout.html
eleventyNavigation:
  key: ProcessSaveSurveyAnswer
  parent: Jobs
---

# ProcessSaveSurveyAnswer

When a question is answered from the Surveys front end a normal request will be made to our Api, which will validate the request data and if everything is correct it will queue this job.

This job will be in charge of storing the data of the answers to the questions, as well as triggering other events to perform extra tasks (such as sending a warning, review or thank you email).

```mermaid
graph TD
    A[Job] --> B{Is this the general question?}
    A --> C["Saving data in the new database system<br>(user_survey_question_answer)"]
    B -->|No| D["Saving data in the old database system<br>(customized_satisfaction_answers)"]
    B -->|Yes| E["Saving data in the old database system<br>(user_satisfaction)"]
    C --> F("Send SurveyQuestionAnsweredEvent<br>(EventBridge)")
    E --> G{Have the thank mail activated?}
    E --> H{Have the review mail activated<br>and the score is higher than <br>the configured minimum score?}
    G -->|Yes| I["Send the event send_satisfaction_thanks_email<br>(old system)"]
    H -->|Yes| J["Send the event send_review_email<br>(old system)"]
    D --> F
    E --> F
```

When we receive an event, first of all we save it in the new database design (in which all the questions coexist, without discriminating whether it is the general or customized question). It is then also stored in the old system (the user_satisfaction table for the general question and the customized_satisfaction_answers table for a customized question). At the moment it is stored like this because there are old functionalities that depend on these tables, and due to development time the data is duplicated for the moment. Ideally, in the future, it will only be stored in the new system.

If it is a general question, we will send events in the old event system (Kinesis, SNS) so that the corresponding mails are sent. Ideally, in the future, these 2 events should be removed and the email platform would be responsible for sending them with the SurveyQuestionAnsweredEvent event, which uses the new event system with EventBridge.

As can be seen, in the future the process will be greatly simplified, reducing this flow to just this:

```mermaid
graph TD
    A[Job] --> B["Saving data in the new database system<br>(user_survey_question_answer)"]
    B --> C("Send SurveyQuestionAnsweredEvent<br>(EventBridge)")
```
