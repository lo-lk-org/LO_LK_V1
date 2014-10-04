SELECT * FROM m_sessions WHERE uid='".$uid."' AND session_die_dt IS NULL;

SELECT UNIX_TIMESTAMP()
=>1411705730
SELECT FROM_UNIXTIME(1411705730);

INSERT INTO `m_profile`(`uid`,`gid`,`fname`,`mname`,`lname`,`name`,`uname`,`email`,`phone`,`timezone`,`img_url`) 
VALUES( '452362345','452362345','Shivaraj','R','H','ShivarajRH','shivarajrh','mrshivaraj123@gmail.com','9590932088',,'')

SELECT * FROM m_sessions WHERE uid='452362345' AND session_die_dt IS NULL

`m_entities`

SELECT * FROM m_profile WHERE gid='4523623456456456' LIMIT 1

UPDATE m_profile SET
	    `fname`='Shivaraj',`mname`='Ranganath',`lname`='Halegowdars',`name`='ShivarajRH',`uname`='shivarajrh'
	    ,`email`='mrshivaraj123@gmail.com',`phone`='9590932088',`timezone`='1411725530',`info_update_timestamp`='2014-09-30 03:27:22',`image_url`=''
    WHERE sno=''
    
    SELECT NOW()

UPDATE `m_content` SET lat='4345.566',`long`='55.85',col_prim_key_name='money_id',tbl_name='m_money',col_prim_key_value='1',tbl_path='m_money' WHERE `content_id`='1'