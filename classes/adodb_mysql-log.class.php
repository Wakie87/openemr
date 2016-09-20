
<?php

/**
* ADODB_mysql class wrapper to ensure proper auditing in OpenEMR.
*
* @author  Kevin Yeh <kevin.y@integralemr.com>
*/
class ADODB_mysqli_log extends ADODB_mysqli
{
        /**
        * ADODB Execute function wrapper to ensure proper auditing in OpenEMR.
        *
        * @param  string  $sql         query
        * @param  array   $inputarr    binded variables array (optional)
        * @return boolean              returns false if error
        */
        function Execute($sql,$inputarr=false)
        {
            $retval= parent::Execute($sql,$inputarr);
            if ($retval === false) {
              $outcome = false;
              // Stash the error into last_mysql_error so it doesn't get clobbered when
              // we insert into the audit log.
              $GLOBALS['last_mysql_error']=$this->ErrorMsg();

              // Last error no
              $GLOBALS['last_mysql_error_no']=$this->ErrorNo();
            }
            else {
              $outcome = true;
            }
            // Stash the insert ID into lastidado so it doesn't get clobbered when
            // we insert into the audit log.
            $GLOBALS['lastidado']=$this->Insert_ID();
            auditSQLEvent($sql,$outcome,$inputarr);
            return $retval;
        }

        /**
        * ADODB Execute function wrapper to skip auditing in OpenEMR.
        *
        * Bypasses the OpenEMR auditing engine.
        *
        * @param  string  $sql         query
        * @param  array   $inputarr    binded variables array (optional)
        * @return boolean              returns false if error
        */
        function ExecuteNoLog($sql,$inputarr=false)
        {
            return parent::Execute($sql,$inputarr);
        }

        /*
        * ADODB GenID function wrapper to work with OpenEMR.
        *
        * Need to override to fix a bug where call to GenID was updating
        * sequences table but always returning a zero with the OpenEMR audit
        * engine both on and off. Note this bug only appears to occur in recent
        * php versions on windows. The fix is to use the ExecuteNoLog() function
        * rather than the Execute() functions within this function (otherwise,
        * there are no other changes from the original ADODB GenID function).
        *
        * @param  string  $seqname     table name containing sequence (default is adodbseq)
        * @param  integer $startID     id to start with for a new sequence (default is 1)
        * @return integer              returns the sequence integer
        */
        function GenID($seqname='adodbseq',$startID=1)
        {
                // post-nuke sets hasGenID to false
                if (!$this->hasGenID) return false;

                $getnext = sprintf($this->_genIDSQL,$seqname);
                $holdtransOK = $this->_transOK; // save the current status
                $rs = @$this->ExecuteNoLog($getnext);
                if (!$rs) {
                        if ($holdtransOK) $this->_transOK = true; //if the status was ok before reset
                        $u = strtoupper($seqname);
                        $this->ExecuteNoLog(sprintf($this->_genSeqSQL,$seqname));
                        $cnt = $this->GetOne(sprintf($this->_genSeqCountSQL,$seqname));
                        if (!$cnt) $this->ExecuteNoLog(sprintf($this->_genSeq2SQL,$seqname,$startID-1));
                        $rs = $this->ExecuteNoLog($getnext);
                }

                if ($rs) {
                        $this->genID = mysqli_insert_id($this->_connectionID);
                        $rs->Close();
                } else
                        $this->genID = 0;

                return $this->genID;
        }
}
