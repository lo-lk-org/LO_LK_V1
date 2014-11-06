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

MID:5
uid:452362345
grpid:4


INSERT INTO `m_permissions` ( `member_id`, `uid`, `group_id`
		    ,`permission_profile`,`permission_notes`,`permission_money`,`permission_todo`,`permission_shopping`,`permission_contacts`,`permission_recipes`,`permission_groups`,`permission_members`
		    ,`permission_services`,`permission_products`,`permission_store`,`permission_orders`
		    ) VALUES 
		    ( '9','452362345','4'
			,'2','0','1','0','0','0','0'
			,'1','1','0','0','0','0'
		    )
		    
SELECT * FROM m_money WHERE money_id='1' AND uid='4523623456456456'

SELECT * FROM m_categories ORDER BY category_name ASC;

SELECT NOW() - INTERVAL 30 DAY
SELECT NOW("")
SELECT INTERVAL(2);

SELECT * FROM m_money m
    WHERE m.uid='4523623456456456'  AND UNIX_TIMESTAMP(m.timestamp) BETWEEN '1410778256' AND '1412160324' 
    ORDER BY m.timestamp ASC
    LIMIT 0,35
    
SELECT * FROM m_money m
    WHERE m.uid='4523623456456456'  AND UNIX_TIMESTAMP(m.timestamp) BETWEEN '1410778256' AND '1413503999' 
    ORDER BY m.timestamp DESC
    LIMIT 0,35

SELECT FROM_UNIXTIME(1410061680);
SELECT FROM_UNIXTIME(1413504000);

SELECT IF(m.timestamp,
	m.* 
	FROM m_money m
	WHERE m.uid='4523623456456456' 
	ORDER BY m.sno DESC;

SELECT * FROM m_money m WHERE m.uid='4523623456456456'  AND (m.timestamp) BETWEEN '2004-12-31 00:00:00' AND '2006-12-31 23:59:59' 
    ORDER BY m.timestamp DESC LIMIT 0,3;
    

SELECT * FROM m_social_contacts WHERE uid='4523623456456456' ORDER BY following_name ASC;


`m_social_contacts`
    
    SELECT * FROM m_money m
					    WHERE m.uid='4523623456456456'  AND (money_title LIKE '%medimix%') AND (m.timestamp) BETWEEN '2014-01-02 00:00:00' AND '2014-10-16 23:59:59' 
					    ORDER BY m.sno DESC LIMIT 0,3