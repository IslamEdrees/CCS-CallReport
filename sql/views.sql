CREATE VIEW vw_call_attempts AS
SELECT
call_time,
agent,
real_number,
dialstatus,
hangupcause,
goip
FROM call_attempts
WHERE agent REGEXP '^[0-9]{4}$';

CREATE OR REPLACE VIEW vw_agent_summary_today AS
SELECT
agent,
COUNT(*) total_calls,
SUM(dialstatus='BUSY') busy_calls,
SUM(dialstatus='NOANSWER') noanswer_calls,
SUM(dialstatus='CONGESTION') congestion_calls,
SUM(dialstatus='CHANUNAVAIL') unavailable_calls
FROM call_attempts
WHERE DATE(call_time)=CURDATE()
AND agent REGEXP '^[0-9]{4}$'
GROUP BY agent;

CREATE OR REPLACE VIEW vw_goip_summary_today AS
SELECT
goip,
COUNT(*) total_calls,
SUM(dialstatus='BUSY') busy_calls,
SUM(dialstatus='NOANSWER') noanswer_calls,
SUM(dialstatus='CONGESTION') congestion_calls,
SUM(dialstatus='CHANUNAVAIL') unavailable_calls
FROM call_attempts
WHERE DATE(call_time)=CURDATE()
AND agent REGEXP '^[0-9]{4}$'
GROUP BY goip;

CREATE OR REPLACE VIEW vw_status_summary_today AS
SELECT
dialstatus,
COUNT(*) total
FROM call_attempts
WHERE DATE(call_time)=CURDATE()
AND agent REGEXP '^[0-9]{4}$'
GROUP BY dialstatus;


