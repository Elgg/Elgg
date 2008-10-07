<?php
	/**
	 * Elgg groups plugin language pack
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$thai = array(
	
		/**
		 * Menu items and titles
		 */
			
			'groups' => "กลุ่ม", 
			'groups:owned' => "กลุ่มที่คุณเป็นเจ้าของ",
			'groups:yours' => "กลุ่มที่สังกัด",
			'groups:user' => "กลุ่ม %s",
			'groups:all' => "กลุ่มทั้งหมด",
			'groups:new' => "สร้างกลุ่มใหม่",
			'groups:edit' => "แก้ไข",
	
			'groups:icon' => 'รูปของกลุ่ม (ว่างไว้ได้หากไม่มี)',
			'groups:name' => 'ชื่อกลุ่ม',
			'groups:username' => 'ชื่อสั้นๆของกลุ่ม (ใช้แสดงใน url ควรใช้ภาษาอังกฤษ)',
			'groups:description' => 'รายละเอียด',
			'groups:briefdescription' => 'รายละเอียดย่อ',
			'groups:interests' => 'ความสนใจ',
			'groups:website' => 'เว็บไซต์',
			'groups:members' => 'สมาชิกของกลุ่ม',
			'groups:membership' => "สมาชิก",
			'groups:access' => "การเข้าถึง",
			'groups:owner' => "เจ้าของ",
	        'groups:widget:num_display' => 'หมายเลขของกลุ่มที่จะแสดง',
	        'groups:widget:membership' => 'สมาชิกของกลุ่ม',
	        'groups:widgets:description' => 'แสดงกลุ่มและสมาชิกในหน้าโปรไฟลื',
			'groups:noaccess' => 'คุณไม่มีสิทธิเข้าถึงกลุ่มนี้',
			'groups:cantedit' => 'คุณไม่สามารถแก้ไขกลุ่มได้',
			'groups:saved' => 'บันทึกกลุ่ม',
	
			'groups:joinrequest' => 'ขอเป็นสมาชิกกลุ่ม',
			'groups:join' => 'สมัครเข้ากลุ่ม',
			'groups:leave' => 'ออกจากกลุ่ม',
			'groups:invite' => 'ชวนเพื่อน',
			'groups:inviteto' => "ชวนเพื่อน '%s'",
			'groups:nofriends' => "คุณยังไม่มีเพื่อน",
	
			'groups:group' => "กลุ่ม",
			
			'item:object:groupforumtopic' => "หัวข้อฟอรั่ม",
	
			/*
			  Group forum strings
			*/
			
			'groups:forum' => 'ฟอรั่มของกลุ่ม',
			'groups:addtopic' => 'เพิ่มหัวข้อ',
			'groups:forumlatest' => 'ล่าสุดในฟอรั่ม',
			'groups:latestdiscussion' => 'การสนทนาล่าสุด',
			'groupspost:success' => 'ความคิดเห็นของคุณบันทึกแล้ว',
			'groups:alldiscussion' => 'การสนทนาล่าสุด',
			'groups:edittopic' => 'แก้ไขหัวข้อ',
			'groups:topicmessage' => 'หัวข้อของข้อความ',
			'groups:topicstatus' => 'สถานะของข้อความ',
			'groups:reply' => 'เขียนความคิดเห็น',
			'groups:topic' => 'หัวข้อ',
			'groups:posts' => 'เขียน',
			'groups:lastperson' => 'ผู้เขียนล่าสุด',
			'groups:when' => 'ที่',
			'grouptopic:notcreated' => 'ยังไม่มีหัวข้อ',
			'groups:topicopen' => 'เปิด',
			'groups:topicclosed' => 'ปิด',
			'groups:topicresolved' => 'ตัดสินแล้ว',
			'grouptopic:created' => 'หัวข้อของคุณสร้างแล้ว',
			'groupstopic:deleted' => 'หัวข้อของคุณลบแล้ว',
			'groups:topicsticky' => 'ยึดหัวข้อ',
			'groups:topicisclosed' => 'หัวข้อนี้ปิดแล้วแล้ว',
			'groups:topiccloseddesc' => 'หัวข้อนี้ปิดแล้วแล้วไม่สามารถแสดงความคิดเห็นได้',
			
	
			'groups:privategroup' => 'กลุ่มนี้เป็นกลุ่มส่วนตัว, คุณต้องส่งคำขอเพื่อเข้ากลุ่ม',
			'groups:notitle' => 'กลุ่มไม่มีชื่อ',
			'groups:cantjoin' => 'ไม่สามารถเข้าร่วมกลุ่มได้',
			'groups:cantleave' => 'ไม่สามารถออกจากกลุ่มได้',
			'groups:addedtogroup' => 'สมาชิกเข้ากลุ่มเรียบร้อยแล้ว',
			'groups:joinrequestnotmade' => 'การร้องขอไม่สามารถทำได้',
			'groups:joinrequestmade' => 'เข้ากลุ่มแล้ว',
			'groups:joined' => 'เข้ากลุ่มแล้ว!',
			'groups:left' => 'ออกจากกลุ่มแล้ว',
			'groups:notowner' => 'เสียใจ, คุณไม่ใช่เจ้าของกลุ่ม',
			'groups:alreadymember' => 'คุณเป็นสมาชิกของกลุ่มแล้ว!',
			'groups:userinvited' => 'สมาชิกส่งคำขอมา',
			'groups:usernotinvited' => 'สมาชิกไม่สามารถส่งคำขอได้',
	
			'groups:invite:subject' => "%s คุณได้รับคำเชิญเพื่อเข้ากลุ่ม %s!",
			'groups:invite:body' => "สวัสดี %s,

คุณได้รับคำเชิญเพื่อเข้ากลุ่ม '%s' คลิ๊กลิ้งค์ด้านล่างเพื่อตอบรับ:

%s",

			'groups:welcome:subject' => "ยินดีต้อนรับสู่กลุ่ม  %s !",
			'groups:welcome:body' => "สวัสดี %s!
		
ตอนนี้คุณเป็นสมาชิกของกลุ่ม '%s' แล้ว! คลิ๊กลิ้งค์ด้านล่างเพื่อโพส!

%s",
	
			'groups:request:subject' => "%s ต้องการเข้ากลุ่ม %s",
			'groups:request:body' => "สวัสดี %s,

%s ต้องการเข้ากลุ่ม '%s' ,คลิ๊กลิ้งค์ด้านล่างเพื่อดูโปรไฟล์:

%s

หรือคลิ๊กลิ้งค์ด้านล่างเพื่อยอมรับเข้ากลุ่ม:

%s",
	
			'groups:river:member' => 'ตอนนี้เป็นสมาชิกของ',
	
			'groups:nowidgets' => 'ไม่มีวิดเจ็ตของกลุ่ม',
	
	
			'groups:widgets:members:title' => 'สมาชิกกลุ่ม',
			'groups:widgets:members:description' => 'รายการของสมาชิกกลุ่ม',
			'groups:widgets:members:label:displaynum' => 'รายการของสมาชิกกลุ่ม',
			'groups:widgets:members:label:pleaseedit' => 'กรุณาตั้งค่าวิดเจ็ต',
	
			'groups:widgets:entities:title' => "ออฟเจ็คในกลุ่ม",
			'groups:widgets:entities:description' => "รายการออฟเจ็คในกลุ่ม",
			'groups:widgets:entities:label:displaynum' => 'รายการออฟเจ็คในกลุ่ม',
			'groups:widgets:entities:label:pleaseedit' => 'กรุณาตั้งค่าวิดเจ็ต',
		
			'groups:forumtopic:edited' => 'แก้ไขหัวข้อของฟอรั่มสำเร็จ',
	);
					
	add_translation("th",$thai);
?>
