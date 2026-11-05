# MySQL Database Initialization & Dump Directory

Directory สำหรับวางไฟล์ฐานข้อมูล MySQL เมื่อเริ่มรัน Docker ครั้งแรก

## การทำงาน
1. สคริปต์ `00-init-grants.sql` จะให้สิทธิ์ `ALL PRIVILEGES` แก่ user `pios` เพื่อรองรับ Multi-Tenant Dynamic Database (`SetTenantConnection`)
2. หากคุณมีไฟล์ Dump ฐานข้อมูลเดิม (`.sql` หรือ `.sql.gz` เช่น `pios_backup.sql` หรือฐานข้อมูล อปท. `envsogo_hs1.sql`):
   - สามารถนำไฟล์มาวางไว้ในโฟลเดอร์นี้ (`docker/mysql/initdb.d/`)
   - เมื่อรัน `docker compose up -d` ครั้งแรก MySQL จะ import ข้อมูลทั้งหมดให้อัตโนมัติตามลำดับชื่อไฟล์
   - Container `app` จะตรวจพบว่ามีตารางในฐานข้อมูลแล้ว และจะข้ามการ Seed ทับโดยอัตโนมัติ เพื่อรักษาข้อมูลจริงไว้
3. หาก Container MySQL เคยถูกสร้างแล้ว (มี Volume เก่าอยู่) และต้องการ Reset ฐานข้อมูลใหม่พร้อมไฟล์ Dump:
   ```bash
   docker compose down -v
   docker compose up -d
   ```
4. หรือสามารถนำเข้าไฟล์ `.sql` ผ่านหน้าเว็บ phpMyAdmin ได้ที่ [http://localhost:8081](http://localhost:8081) โดยตรง
