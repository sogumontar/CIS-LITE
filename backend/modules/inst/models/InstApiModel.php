<?php

namespace backend\modules\inst\models;

use Yii;
use backend\modules\inst\models\StrukturJabatan;
use backend\modules\inst\models\Pejabat;
use backend\modules\inst\models\Pegawai;
use backend\modules\inst\models\Unit;
use yii\base\Model;


class InstApiModel extends Model
{


    /*
        GET PEJABAT BY STRUKTUR JABATAN ID
        Return => Array of Pejabat
    */
    public function getPejabatByJabatan($jabatan_id, $instansi_id=null){

        $current_date=date('Y-m-d');

        $pejabat = Pejabat::find()->where(['struktur_jabatan_id' => $jabatan_id])->andWhere(['<=', 'awal_masa_kerja', $current_date])->andWhere(['>=', 'akhir_masa_kerja', $current_date])->andWhere(['status_aktif' => 1])->andWhere('deleted != 1')->orderBy(['created_at' => SORT_DESC])->all();

        return $pejabat;
    }


    /*
        GET PEJABAT BY PEGAWAI ID & STRUKTUR JABATAN ID
        Return => Pejabat
    */
    public function getPejabatByPegawaiIdJabatan($pegawai_id, $jabatan_id, $instansi_id=null){

        $current_date=date('Y-m-d');

        $pejabat = Pejabat::find()->where(['pegawai_id' => $pegawai_id, 'struktur_jabatan_id' => $jabatan_id])->andWhere(['<=', 'awal_masa_kerja', $current_date])->andWhere(['>=', 'akhir_masa_kerja', $current_date])->andWhere(['status_aktif' => 1])->andWhere('deleted != 1')->orderBy(['created_at' => SORT_DESC])->one();

        return $pejabat;
    }

    /*
        GET PEJABAT BY PEGAWAI ID
        Return => array of Pejabat
    */
    public function getPejabatByPegawaiId($pegawai_id, $instansi_id=null){

        $current_date=date('Y-m-d');

        $pejabat = Pejabat::find()->joinWith('strukturJabatan')->where(['pegawai_id' => $pegawai_id])->andWhere(['<=', 'awal_masa_kerja', $current_date])->andWhere(['>=', 'akhir_masa_kerja', $current_date])->andWhere(['status_aktif' => 1])->andWhere('inst_pejabat.deleted != 1')->orderBy(['inst_struktur_jabatan.jabatan' => SORT_ASC])->all();

        return $pejabat;
    }

    /*
        GET STRUKTUR JABATAN BY PEGAWAI ID
        Return => array of StrukturJabatan
    */
    public function getJabatanByPegawaiId($pegawai_id, $instansi_id=null){

        $current_date=date('Y-m-d');

        $pejabat = Pejabat::find()->select('struktur_jabatan_id')->where(['pegawai_id' => $pegawai_id])->andWhere(['<=', 'awal_masa_kerja', $current_date])->andWhere(['>=', 'akhir_masa_kerja', $current_date])->andWhere(['status_aktif' => 1])->andWhere('deleted != 1')->all();

        $struktur = StrukturJabatan::find()->where(['in', 'struktur_jabatan_id', $pejabat])->andWhere('deleted != 1')->orderBy(['jabatan' => SORT_ASC])->all();
        if($instansi_id!=null)
            $struktur = StrukturJabatan::find()->where(['instansi_id' => $instansi_id])->andWhere(['in', 'struktur_jabatan_id', $pejabat])->andWhere('deleted != 1')->orderBy(['jabatan' => SORT_ASC])->all();

        return $struktur;
    }


    /*
        GET ATASAN BY PEGAWAI ID
        Return => array of Pegawai
    */
    public function getAtasanByPegawaiId($pegawai_id, $instansi_id=null){

        $current_date=date('Y-m-d');

        $pejabat = Pejabat::find()->select('struktur_jabatan_id')->where(['pegawai_id' => $pegawai_id])->andWhere(['<=', 'awal_masa_kerja', $current_date])->andWhere(['>=', 'akhir_masa_kerja', $current_date])->andWhere(['status_aktif' => 1])->andWhere('deleted != 1')->all();

        $struktur = StrukturJabatan::find()->select('parent as struktur_jabatan_id')->where(['in', 'struktur_jabatan_id', $pejabat])->andWhere('deleted != 1')->all();
        if($instansi_id!=null)
            $struktur = StrukturJabatan::find()->select('parent as struktur_jabatan_id')->where(['instansi_id' => $instansi_id])->andWhere(['in', 'struktur_jabatan_id', $pejabat])->andWhere('deleted != 1')->all();

        $parent = Pejabat::find()->select('pegawai_id')->where(['in', 'struktur_jabatan_id', $struktur])->andWhere(['<=', 'awal_masa_kerja', $current_date])->andWhere(['>=', 'akhir_masa_kerja', $current_date])->andWhere(['status_aktif' => 1])->andWhere('deleted != 1')->all();

        $pegawai = Pegawai::find()->where(['in', 'pegawai_id', $parent])->andWhere('deleted != 1')->all();

        return $pegawai;
    }


    /*
        GET BAWAHAN BY PEGAWAI ID
        Return => array of Pegawai
    */
    public function getBawahanByPegawaiId($pegawai_id, $instansi_id=null){

        $current_date=date('Y-m-d');

        $pejabat = Pejabat::find()->select('struktur_jabatan_id')->where(['pegawai_id' => $pegawai_id])->andWhere(['<=', 'awal_masa_kerja', $current_date])->andWhere(['>=', 'akhir_masa_kerja', $current_date])->andWhere(['status_aktif' => 1])->andWhere('deleted != 1')->all();

        $pejabats=array();
        foreach($pejabat as $p)
            $pejabats[]=$p->struktur_jabatan_id;

        $struktur = StrukturJabatan::find()->select('struktur_jabatan_id')->where(['in', 'parent', $pejabats])->andWhere('deleted != 1')->all();
        if($instansi_id!=null)
            $struktur = StrukturJabatan::find()->select('struktur_jabatan_id')->where(['instansi_id' => $instansi_id])->andWhere(['in', 'parent', $pejabats])->andWhere('deleted != 1')->all();

        $bawahan = Pejabat::find()->select('pegawai_id')->where(['in', 'struktur_jabatan_id', $struktur])->andWhere(['<=', 'awal_masa_kerja', $current_date])->andWhere(['>=', 'akhir_masa_kerja', $current_date])->andWhere(['status_aktif' => 1])->andWhere('deleted != 1')->all();

        $pegawai = Pegawai::find()->where(['in', 'pegawai_id', $bawahan])->andWhere('deleted != 1')->all();

        return $pegawai;
    }


    /*
        GET ATASAN BY STRUKTUR JABATAN ID
        Return => StrukturJabatan
    */
    public function getAtasanByJabatan($struktur_jabatan_id){

        $struktur = StrukturJabatan::find()->where(['struktur_jabatan_id' => $struktur_jabatan_id])->andWhere('deleted != 1')->one();

        $parent = StrukturJabatan::find()->where(['struktur_jabatan_id' => $struktur->parent])->andWhere('deleted != 1')->one();

        return $parent;
    }


    /*
        GET BAWAHAN BY STRUKTUR JABATAN ID
        Return => array of StrukturJabatan
    */
    public function getBawahanByJabatan($struktur_jabatan_id){

        $struktur = StrukturJabatan::find()->where(['parent' => $struktur_jabatan_id])->andWhere('deleted != 1')->orderBy(['jabatan' => SORT_ASC])->all();

        return $struktur;
    }


    /*
        GET MASA JABATAN
        Return => {"awal_masa_kerja":"","akhir_masa_kerja":""}
    */
    public function getMasaJabatan($pegawai_id, $struktur_jabatan_id, $instansi_id=null){

        $current_date=date('Y-m-d');

        $pejabat = Pejabat::find()->select(['awal_masa_kerja', 'akhir_masa_kerja'])->where(['pegawai_id' => $pegawai_id, 'struktur_jabatan_id' => $struktur_jabatan_id])->andWhere(['<=', 'awal_masa_kerja', $current_date])->andWhere(['>=', 'akhir_masa_kerja', $current_date])->andWhere(['status_aktif' => 1])->andWhere('deleted != 1')->orderBy(['created_at' => SORT_DESC])->one();

        return $pejabat;
    }


    /*
        GET ALL UNIT
        Return => array of Unit
    */
    public function getAllUnit($instansi_id){

        $unit = Unit::find()->where(['instansi_id' => $instansi_id])->andWhere('deleted != 1')->all();

        return $unit;
    }


    /*
        GET KEPALA UNIT
        Return => array of Pegawai
    */
    public function getKepalaUnit($unit_id){

        $current_date=date('Y-m-d');

        $unit = Unit::find()->where(['unit_id' => $unit_id])->andWhere('deleted != 1')->one();

        $pejabat = Pejabat::find()->select('pegawai_id')->where(['struktur_jabatan_id' => $unit->kepala])->andWhere(['<=', 'awal_masa_kerja', $current_date])->andWhere(['>=', 'akhir_masa_kerja', $current_date])->andWhere(['status_aktif' => 1])->andWhere('deleted != 1')->all();

        $pegawai = Pegawai::find()->where(['in', 'pegawai_id', $pejabat])->andWhere('deleted != 1')->all();

        return $pegawai;
    }
}
