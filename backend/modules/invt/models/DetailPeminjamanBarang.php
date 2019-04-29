<?php

namespace backend\modules\invt\models;

use Yii;
use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

use backend\modules\invt\models\PeminjamanBarang;
use backend\modules\invt\models\Barang;
/**
 * This is the model class for table "invt_detail_peminjaman_barang".
 *
 * @property integer $detail_peminjaman_barang_id
 * @property integer $peminjaman_barang_id
 * @property integer $barang_id
 * @property integer $jumlah
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $created_by
 * @property string $created_at
 *
 * @property InvtPeminjamanBarang $peminjamanBarang
 * @property InvtBarang $barang
 */
class DetailPeminjamanBarang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invt_detail_peminjaman_barang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['peminjaman_barang_id', 'barang_id', 'jumlah', 'jumlah_rusak'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'detail_peminjaman_barang_id' => 'Detail Peminjaman Barang ID',
            'peminjaman_barang_id' => 'Peminjaman Barang ID',
            'jumlah_rusak'=>'Jumlah Rusak',
            'barang_id' => 'Barang ID',
            'jumlah' => 'Jumlah',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeminjamanBarang()
    {
        return $this->hasOne(PeminjamanBarang::className(), ['peminjaman_barang_id' => 'peminjaman_barang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBarang()
    {
        return $this->hasOne(Barang::className(), ['barang_id' => 'barang_id']);
    }
}
