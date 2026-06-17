CREATE TABLE call_attempts
(
id BIGINT AUTO_INCREMENT PRIMARY KEY,

call_time DATETIME NOT NULL,

agent VARCHAR(20),

channel VARCHAR(100),

tracknum VARCHAR(50),

uniqueid VARCHAR(50),

exten VARCHAR(50),

real_number VARCHAR(30),

dialstatus VARCHAR(30),

hangupcause INT,

goip VARCHAR(20),

start_time DATETIME,

end_time DATETIME,

duration INT
);

CREATE INDEX idx_time
ON call_attempts(call_time);

CREATE INDEX idx_agent
ON call_attempts(agent);

CREATE INDEX idx_number
ON call_attempts(real_number);

CREATE INDEX idx_status
ON call_attempts(dialstatus);

CREATE INDEX idx_track
ON call_attempts(tracknum);
