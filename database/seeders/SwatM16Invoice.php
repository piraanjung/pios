<?php

namespace Database\Seeders;

use App\Models\Tabwater\TwMeterInfos;
use App\Models\Tabwater\TwInvoice;
use App\Models\Tabwater\InvoicePeriod;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SwatM16Invoice extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $curAndLastMeters = [

      ["meter_id_fk" =>  16001,  "firstname" =>  "นายจำรัส บุญทศ",  "lastmeter" =>  3041,  "currentmeter" =>  3048],
      ["meter_id_fk" =>  16002,  "firstname" =>  "นายสีหา ผ่านเมือง",  "lastmeter" =>  2328,  "currentmeter" =>  2330],
      ["meter_id_fk" =>  16003,  "firstname" =>  "นางประกายแก้ว ยิ่งยง(ห้อง1)",  "lastmeter" =>  739,  "currentmeter" =>  742],
      ["meter_id_fk" =>  16004,  "firstname" =>  "นางประกายแก้ว ยิ่งยง(ห้อง2)",  "lastmeter" =>  841,  "currentmeter" =>  844],
      ["meter_id_fk" =>  16005,  "firstname" =>  "นางประกายแก้ว ยิ่งยง (ห้อง3)",  "lastmeter" =>  1617,  "currentmeter" =>  1622],
      ["meter_id_fk" =>  16006,  "firstname" =>  "นายอิธิพล อินเรืองนาม",  "lastmeter" =>  5219,  "currentmeter" =>  5254],
      ["meter_id_fk" =>  16007,  "firstname" =>  "นายพรประเสริฐศักดิ์ ทรงสังขาร",  "lastmeter" =>  805,  "currentmeter" =>  806],
      ["meter_id_fk" =>  16008,  "firstname" =>  "นายวิรัส วิลาวรรณ",  "lastmeter" =>  2284,  "currentmeter" =>  2291],
      ["meter_id_fk" =>  16009,  "firstname" =>  "นายคำอูน บุญทศ(1)",  "lastmeter" =>  1706,  "currentmeter" =>  1710],
      ["meter_id_fk" =>  16010,  "firstname" =>  "นายคำอูน บุญทศ(2)",  "lastmeter" =>  557,  "currentmeter" =>  557],
      ["meter_id_fk" =>  16011,  "firstname" =>  "นางสมพร บุญเพิ่ม",  "lastmeter" =>  1263,  "currentmeter" =>  1263],
      ["meter_id_fk" =>  16012,  "firstname" =>  "นายคำหมื่น ขุมคำ",  "lastmeter" =>  1619,  "currentmeter" =>  1621],
      ["meter_id_fk" =>  16013,  "firstname" =>  "นางพิลัยรัก แสนทวีสุข",  "lastmeter" =>  4079,  "currentmeter" =>  4083],
      ["meter_id_fk" =>  16014,  "firstname" =>  "นายหยาย แก้วโมรี",  "lastmeter" =>  711,  "currentmeter" =>  771],
      ["meter_id_fk" =>  16015,  "firstname" =>  "นายประภา แก้วโมรี",  "lastmeter" =>  1117,  "currentmeter" =>  1117],
      ["meter_id_fk" =>  16016,  "firstname" =>  "นางลัดดาวัลย์ แก้วโมรี",  "lastmeter" =>  14436,  "currentmeter" =>  14436],
      ["meter_id_fk" =>  16017,  "firstname" =>  "นางสมเวียง จากผา",  "lastmeter" =>  2863,  "currentmeter" =>  2868],
      ["meter_id_fk" =>  16018,  "firstname" =>  "นางอรชร ดีแสน",  "lastmeter" =>  2132,  "currentmeter" =>  2142],
      ["meter_id_fk" =>  16019,  "firstname" =>  "นางสุมาลี พะโคไช",  "lastmeter" =>  901,  "currentmeter" =>  902],
      ["meter_id_fk" =>  16020,  "firstname" =>  "นางสุขสันต์ พูลพัน",  "lastmeter" =>  2523,  "currentmeter" =>  2523],
      ["meter_id_fk" =>  16021,  "firstname" =>  "นายสายยนต์ วงศาจารย์",  "lastmeter" =>  2068,  "currentmeter" =>  2068],
      ["meter_id_fk" =>  16022,  "firstname" =>  "นายสำเริง ทาสีเพชร",  "lastmeter" =>  1923,  "currentmeter" =>  1923],
      ["meter_id_fk" =>  16023,  "firstname" =>  "นางลัดดา  สุระพร",  "lastmeter" =>  589,  "currentmeter" =>  589],
      ["meter_id_fk" =>  16024,  "firstname" =>  "นายมุกดา บุญทิพย์",  "lastmeter" =>  698,  "currentmeter" =>  698],
      ["meter_id_fk" =>  16026,  "firstname" =>  "นายโสม ผ่านเมือง",  "lastmeter" =>  1617,  "currentmeter" =>  1617],
      ["meter_id_fk" =>  16027,  "firstname" =>  "นายแดนไพร อนุกุลประชา",  "lastmeter" =>  959,  "currentmeter" =>  959],
      ["meter_id_fk" =>  16028,  "firstname" =>  "นางคำมี จันลาชัย",  "lastmeter" =>  3683,  "currentmeter" =>  3683],
      ["meter_id_fk" =>  16029,  "firstname" =>  "นางสุปนา ผ่านเมือง",  "lastmeter" =>  1591,  "currentmeter" =>  1604],
      ["meter_id_fk" =>  16030,  "firstname" =>  "นางลำดวน อินเรืองนาม",  "lastmeter" =>  2387,  "currentmeter" =>  2387],
      ["meter_id_fk" =>  16031,  "firstname" =>  "นายอนุทัต ทาสีเพชร",  "lastmeter" =>  2013,  "currentmeter" =>  2015],
      ["meter_id_fk" =>  16032,  "firstname" =>  "นายคำไข แนวจำปา",  "lastmeter" =>  3194,  "currentmeter" =>  3205],
      ["meter_id_fk" =>  16033,  "firstname" =>  "นางสุภรักษ์ ผ่านเมือง",  "lastmeter" =>  1496,  "currentmeter" =>  1496],
      ["meter_id_fk" =>  16034,  "firstname" =>  "นางเสลียง เคยสนิจ",  "lastmeter" =>  177,  "currentmeter" =>  177],
      ["meter_id_fk" =>  16035,  "firstname" =>  "นางไพบูลย์ แก้วแสน",  "lastmeter" =>  785,  "currentmeter" =>  785],
      ["meter_id_fk" =>  16036,  "firstname" =>  "นายทอง โสมอินทร์",  "lastmeter" =>  2024,  "currentmeter" =>  2024],
      ["meter_id_fk" =>  16038,  "firstname" =>  "นางอภิวรรณ ทาสีเพชร",  "lastmeter" =>  926,  "currentmeter" =>  926],
      ["meter_id_fk" =>  16039,  "firstname" =>  "นางประภาพร แนตรวงศ์",  "lastmeter" =>  3515,  "currentmeter" =>  3531],
      ["meter_id_fk" =>  16040,  "firstname" =>  "นายเฉลิม  ขยันหา",  "lastmeter" =>  2585,  "currentmeter" =>  2598],
      ["meter_id_fk" =>  16041,  "firstname" =>  "นางอุบลรัตน์ คำนันดา",  "lastmeter" =>  1741,  "currentmeter" =>  1741],
      ["meter_id_fk" =>  16042,  "firstname" =>  "นายธเนตร  ศรีโย",  "lastmeter" =>  1148,  "currentmeter" =>  1153],
      ["meter_id_fk" =>  16043,  "firstname" =>  "นางจันทร์เรือง สรรพศิริมงคล",  "lastmeter" =>  920,  "currentmeter" =>  920],
      ["meter_id_fk" =>  16044,  "firstname" =>  "นางลัดดาวัลย์ แก้วโมรี",  "lastmeter" =>  1839,  "currentmeter" =>  1849],
      ["meter_id_fk" =>  16045,  "firstname" =>  "นางลอนดอน ทาสีเพชร",  "lastmeter" =>  44,  "currentmeter" =>  44],
      ["meter_id_fk" =>  16046,  "firstname" =>  "นางจวงจันทร์ ทาสีเพชร",  "lastmeter" =>  843,  "currentmeter" =>  843],
      ["meter_id_fk" =>  16047,  "firstname" =>  "นายธีระศักดิ์  โคตรพันธ",  "lastmeter" =>  5497,  "currentmeter" =>  5532],
      ["meter_id_fk" =>  16048,  "firstname" =>  "นายวิวัฒน์  ศรีบุญนำ",  "lastmeter" =>  1296,  "currentmeter" =>  1303],
      ["meter_id_fk" =>  16049,  "firstname" =>  "นางมี นามคำ",  "lastmeter" =>  310,  "currentmeter" =>  310],
      ["meter_id_fk" =>  16050,  "firstname" =>  "นายวินิจฉัย  ไชยสิทธิ์",  "lastmeter" =>  1230,  "currentmeter" =>  1230],
      ["meter_id_fk" =>  16051,  "firstname" =>  "นายทองจันทร์ นัยจิต",  "lastmeter" =>  1944,  "currentmeter" =>  1946],
      ["meter_id_fk" =>  16052,  "firstname" =>  "นายวิเชียร ทองเหลา",  "lastmeter" =>  1704,  "currentmeter" =>  1720],
      ["meter_id_fk" =>  16053,  "firstname" =>  "นางสุวรรณ์ ผ่านเมือง ",  "lastmeter" =>  2130,  "currentmeter" =>  2131],
      ["meter_id_fk" =>  16054,  "firstname" =>  "นายคำนัย เนตรวงศ์",  "lastmeter" =>  3183,  "currentmeter" =>  3183],
      ["meter_id_fk" =>  16055,  "firstname" =>  "นางทองสาย สมคะเณย์",  "lastmeter" =>  1720,  "currentmeter" =>  1731],
      ["meter_id_fk" =>  16056,  "firstname" =>  "นายเรืองเดช นัยจิต",  "lastmeter" =>  1264,  "currentmeter" =>  1264],
      ["meter_id_fk" =>  16057,  "firstname" =>  "นายสนิท  โพธิ์มาตย์ ",  "lastmeter" =>  751,  "currentmeter" =>  751],
      ["meter_id_fk" =>  16058,  "firstname" =>  "นายบุญอุ้ม โสมอินทร์",  "lastmeter" =>  142,  "currentmeter" =>  143],
      ["meter_id_fk" =>  16059,  "firstname" =>  "นางสาวลักษณา ผงทอง",  "lastmeter" =>  724,  "currentmeter" =>  743],
      ["meter_id_fk" =>  16060,  "firstname" =>  "นางเสาวลักษณ์  สมคะเณย์",  "lastmeter" =>  89,  "currentmeter" =>  97],
      ["meter_id_fk" =>  16061,  "firstname" =>  "นางรจนา สุขสุพรรณ",  "lastmeter" =>  433,  "currentmeter" =>  433],
      ["meter_id_fk" =>  16062,  "firstname" =>  "นางสาวรุ่งทิวา ไชยสัจ",  "lastmeter" =>  1572,  "currentmeter" =>  1587],
      ["meter_id_fk" =>  16063,  "firstname" =>  "นางคำศรี ศรีสมบูรณ์",  "lastmeter" =>  204,  "currentmeter" =>  207],
      ["meter_id_fk" =>  16064,  "firstname" =>  "นางยอดแก้ว โสมอินทร์ ",  "lastmeter" =>  29,  "currentmeter" =>  30],
      ["meter_id_fk" =>  16065,  "firstname" =>  "นายวินิจฉัย  ไชยสิทธิ์",  "lastmeter" =>  931,  "currentmeter" =>  967],
      ["meter_id_fk" =>  16066,  "firstname" =>  "นางสำราญ สุริรักษ์",  "lastmeter" =>  4962,  "currentmeter" =>  4962],
      ["meter_id_fk" =>  16067,  "firstname" =>  "นางสาวอำพร นารีรบ",  "lastmeter" =>  597,  "currentmeter" =>  612],
      ["meter_id_fk" =>  16069,  "firstname" =>  "นายสวัสดิ์ นามคำ",  "lastmeter" =>  747,  "currentmeter" =>  748],
      ["meter_id_fk" =>  16070,  "firstname" =>  "นางพิชารักษ์ ศรีสมบูรณ์",  "lastmeter" =>  1679,  "currentmeter" =>  1679],
      ["meter_id_fk" =>  16072,  "firstname" =>  "นางอัมภา แสงฉวี",  "lastmeter" =>  1875,  "currentmeter" =>  1876],
      ["meter_id_fk" =>  16073,  "firstname" =>  "นางจันทร์ นามคำ",  "lastmeter" =>  571,  "currentmeter" =>  571],
      ["meter_id_fk" =>  16074,  "firstname" =>  "นางทองศรี คูณสิงห์",  "lastmeter" =>  1294,  "currentmeter" =>  1294],
      ["meter_id_fk" =>  16075,  "firstname" =>  "นางอรทิน โคตรวันทา",  "lastmeter" =>  1897,  "currentmeter" =>  1897],
      ["meter_id_fk" =>  16076,  "firstname" =>  "นางอรทัย ชื่นตา",  "lastmeter" =>  3830,  "currentmeter" =>  3838],
      ["meter_id_fk" =>  16077,  "firstname" =>  "นางประกายแก้ว ยิ่งยง",  "lastmeter" =>  2633,  "currentmeter" =>  2633],
      ["meter_id_fk" =>  16078,  "firstname" =>  "นางพิสมัย ยิ่งยง",  "lastmeter" =>  1442,  "currentmeter" =>  1442],
      ["meter_id_fk" =>  16079,  "firstname" =>  "นายลำเลียง แสงฉวี",  "lastmeter" =>  1628,  "currentmeter" =>  1628],
      ["meter_id_fk" =>  16080,  "firstname" =>  "นายอ่อนตา พรมจันทร์",  "lastmeter" =>  763,  "currentmeter" =>  763],
      ["meter_id_fk" =>  16081,  "firstname" =>  "นายแถลง บุญทศ",  "lastmeter" =>  1272,  "currentmeter" =>  1275],
      ["meter_id_fk" =>  16082,  "firstname" =>  "นางธนิกา มาลาพันธ์",  "lastmeter" =>  2028,  "currentmeter" =>  2028],
      ["meter_id_fk" =>  16083,  "firstname" =>  "นางบูรนวีย์ จันทร์นัย",  "lastmeter" =>  2712,  "currentmeter" =>  2712],
      ["meter_id_fk" =>  16084,  "firstname" =>  "นายประดิษฐ์ บรรหารบุตร",  "lastmeter" =>  579,  "currentmeter" =>  579],
      ["meter_id_fk" =>  16085,  "firstname" =>  "นางกลอยใจ บรรหารบุตร",  "lastmeter" =>  1933,  "currentmeter" =>  1933],
      ["meter_id_fk" =>  16086,  "firstname" =>  "นายบรรยงค์ ประเชิญสุข",  "lastmeter" =>  1314,  "currentmeter" =>  1314],
      ["meter_id_fk" =>  16087,  "firstname" =>  "นายธวัชชัย กลบกลิ่น",  "lastmeter" =>  720,  "currentmeter" =>  720],
      ["meter_id_fk" =>  16088,  "firstname" =>  "นางทองจันทร์ สายหมั่น",  "lastmeter" =>  2541,  "currentmeter" =>  2541],
      ["meter_id_fk" =>  16089,  "firstname" =>  "นายวิมลศักดิ์ ยิ่งยง",  "lastmeter" =>  470,  "currentmeter" =>  470],
      ["meter_id_fk" =>  16090,  "firstname" =>  "นายโอรอน เวชกามา",  "lastmeter" =>  396,  "currentmeter" =>  398],
      ["meter_id_fk" =>  16091,  "firstname" =>  "นางอุดมศรี อยู่สุข",  "lastmeter" =>  807,  "currentmeter" =>  807],
      ["meter_id_fk" =>  16093,  "firstname" =>  "นางกร จากผา",  "lastmeter" =>  6241,  "currentmeter" =>  6246],
      ["meter_id_fk" =>  16095,  "firstname" =>  "นายจีระศักดิ์ จารุสาร",  "lastmeter" =>  941,  "currentmeter" =>  943],
      ["meter_id_fk" =>  16097,  "firstname" =>  "นายรุ่งระพี นำพา",  "lastmeter" =>  906,  "currentmeter" =>  906],
      ["meter_id_fk" =>  16098,  "firstname" =>  "นางพิม โสมอินทร์",  "lastmeter" =>  2587,  "currentmeter" =>  2604],
      ["meter_id_fk" =>  16099,  "firstname" =>  "นางไข่มุก พรมนา",  "lastmeter" =>  597,  "currentmeter" =>  597],
      ["meter_id_fk" =>  16100,  "firstname" =>  "นายอำนวยผล จากผา(2)",  "lastmeter" =>  182,  "currentmeter" =>  182],
      ["meter_id_fk" =>  16104,  "firstname" =>  "นายอำนวยผล จากผา(1)",  "lastmeter" =>  919,  "currentmeter" =>  919],
      ["meter_id_fk" =>  16105,  "firstname" =>  "นายสิริภูมิ จากผา(2)",  "lastmeter" =>  2442,  "currentmeter" =>  2468],
      ["meter_id_fk" =>  16106,  "firstname" =>  "นายเสวียง จากผา",  "lastmeter" =>  1866,  "currentmeter" =>  1866],
      ["meter_id_fk" =>  16108,  "firstname" =>  "นางเกตุมะณี คำเสมอ",  "lastmeter" =>  1127,  "currentmeter" =>  1135],

    ];
    $invperiod = InvoicePeriod::where('org_id_fk', 4)->where('status', 'active')->first();
    $i = TwInvoice::max('id')  + 1;
    foreach ($curAndLastMeters as $curAndLastMeter) {
      $used = $curAndLastMeter['currentmeter'] - $curAndLastMeter['lastmeter'];
      TwInvoice::create([
        'org_id_fk' => 4,
        'inv_no' => $i,
        'inv_period_id_fk' => $invperiod->id,
        'meter_id_fk' => $curAndLastMeter['meter_id_fk'],
        'lastmeter' => $curAndLastMeter['lastmeter'],
        'currentmeter' => $curAndLastMeter['currentmeter'],
        'water_used' => $used,
        'reserve_meter' => 0,
        'inv_type' => $used > 0 ? 'u' : 'r',
        'paid' => $used * 8,
        'vat' => 0,
        'totalpaid' => $used * 8,
        'acc_trans_id_fk' => 0,
        'recorder_id' => 1,
        'previous_balance' => 0,
        'status' => 'invoice',
        'created_at' => now(),
        'updated_at' => now(),
      ]);
      $i++;
    }
  }
}
