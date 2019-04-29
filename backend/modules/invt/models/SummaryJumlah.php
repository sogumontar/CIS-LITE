<?php

namespace backend\modules\invt\models;

use Yii;

/**
 * This is the model class for table "invt_summary_jumlah".
 *
 * @property integer $summary_jumlah_id
 * @property integer $barang_id
 * @property integer $kategori_id
 * @property integer $total_jumlah
 * @property integer $jumlah_distribusi
 * @property integer $jumlah_rusak
 * @property integer $jumlah_pinjam
 * @property integer $jumlah_musnah
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $created_by
 * @property string $created_at
 *
 * @property InvtBarang $barang
 */
class SummaryJumlah extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invt_summary_jumlah';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['barang_id', 'kategori_id'], 'required'],
            [['barang_id', 'kategori_id', 'total_jumlah', 'jumlah_distribusi','jumlah_gudang', 'jumlah_rusak', 'jumlah_pinjam', 'jumlah_musnah', 'deleted'], 'integer'],
            [['deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'summary_jumlah_id' => 'Summary Jumlah ID',
            'barang_id' => 'Barang ID',
            'kategori_id' => 'Kategori ID',
            'total_jumlah' => 'Total Jumlah',
            'jumlah_distribusi' => 'Jumlah Distribusi',
            'jumlah_rusak' => 'Jumlah Rusak',
            'jumlah_pinjam' => 'Jumlah Pinjam',
            'jumlah_musnah' => 'Jumlah Musnah',
            'deleted' => 'Deleted',
            'deleted_by' => 'Deleted By',
            'deleted_at' => 'Deleted At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBarang()
    {
        return $this->hasOne(Barang::className(), ['barang_id' => 'barang_id']);
    }
}
