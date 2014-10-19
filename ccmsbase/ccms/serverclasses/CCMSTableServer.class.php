<?php
/***************************************************************************
 *   Copyright (C) 2006-2007, Christoph Herrmann (theone@csherrmann.com)   *
 *                                                                         *
 *                                                                         *
 *   CCMS is free software; you can redistribute it and/or modify          *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 3 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   CCMS is distributed in the hope that it will be useful,               *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with CCMS. If not, see <http://www.gnu.org/licenses/>           *
 ***************************************************************************/

/** @addtogroup Serverclasses
* @{
*/

/**
* @file CCMSTableServer.class.php
* Server part of the CCMS Table Object.
*/

/**
* The serverpart for the CCMS Table Object.
* @see table.php on how this class is used
*/

abstract class CCMSTableServer extends CCMSGenericServer
{
  /**
	* Adds a new entry filled with standart values.
	* Real values will be filled in via edit. Makes sense if you consider the user only clicks the "add" link to create a new row.<BR>
	* You can also add "standard" values to the newly created row in here. Just add them as done in the GUI object.<BR>
	* @returns the id of the new row
	* @see table.php
	*/
	abstract public function add_new();

  /**
	* Takes care of deleting one data row.
	* The database id will be handed over via id. This function only needs to take care of deleting it.
	* @param $id the databaseid
	* @returns void
	* @see table.php
	*/
	abstract public function del_me($id);

  /**
	* Updates a row.
	* $vars contains a $_POST array with the values set up like this:<BR>
	* $vars['ccms_t0'], $vars['ccms_t1'] etc. <BR>
	* Note: only real elements are included in numbering, meaning for example CCMSRawTextElements <BR>
	* are excluded and will not "break" the numbering<BR>
	* $vars['id'] contains the databaseid as set during adding the row.
	* @param &$vars the variable array.
	* @returns void
	* @see table.php
	*/
	abstract public function update_me(&$vars);

	/**
	* Determines where to put the row.
	* If the moverow option is set to true, there has to be a way to determine where an updated row should be put into<BR>
	* This needs to be found out in this function and returned a specific style, which is:<BR>
	* 0-> insert at the end <BR> 1 -> insert before <BR> 2 -> insert after <BR> 3 -> insert at the top<BR>
	* As for nr 1 & 2, $point must be set as well, in order to find the right position.<BR>
	* The $point variable is the rowid you set up in the GUI object. (e.g an `id` column on a database)
	* @param &$vals the new values
	* @returns array($point, $type) (further description in details)
	*/
	abstract public function moverow(&$vals);

	/**
	* Rowid storage.
	* @see See CCMSRowIDStorage for further information
	*/
	protected $rowstorage;	

	/**
	* Tablestorage.
	* @see See CCMSTableStorage for further information
	*/
	protected $tablestorage;

	/**
	* Moverow setting for the CCMS Object.
	* If this is true, all entries will be sorted after insert<BR>
	* This is particulary usefull for dates...
	*/
	protected $moverow;

	/**
	* Temporary storage for a newly created rowid.
	* Makes the add_tmp_val() and add_dyntag() function work
	* @see CCMSTableServer::add_tmp_val(), CCMSTableServer::add_dyntag()
	*/
	protected $tmp_newrowid;

	/**
	* Magic function for serialize().
	* Returns all variables to be saved when serializing.
	*/
	public function __sleep()
	{
		return array_merge ( parent::__sleep(), array('tablestorage', 'rowstorage', 'moverow'));
	}


	/**
	* The constructor.
	* @param $valstor the serialized CCMSElementValueStorage Object, the same as created for the GUI
	* @param $tblstor the serialized CCMSTableStorage element
	* @param $rowstor serialized CCMSRowIDStorage 
	* @param $moverow move rows after insert or not
	* @returns void
	*/
	function __construct($valstor, $tblstor, $rowstor, $moverow)
	{
		parent::__construct($valstor);
		$this->tablestorage = unserialize($tblstor);
		$this->rowstorage = unserialize($rowstor);
		$this->moverow = $moverow;
	}

	/**
	* Sets the $tmp_newrowid variable.
	* @param $val the value to set it to.
	* @returns void
	* @see $tmp_newrowid
	*/	
	public function set_tmp_newrowid($val){ $this->tmp_newrowid = $val; }
	
	/**
	* Adds a temporary default value to a newly created element. 
	* Basically rerouting to the CCMSElementValueStorage element.
	* @param $elementid the elementid of the element for which the value is set
	* @param $val the value itself
	* @returns void
	* @see CCMSElementValueStorage
	*/
	protected function add_tmp_val($elementid, $val)
	{
		if(is_numeric($this->tmp_newrowid))
			$this->elements->add_value($elementid, $val, $this->tmp_newrowid);	
	}
	
	/**
	* Adds a dyntag to a newly created element. 
	* Basically rerouting to the CCMSElementValueStorage element.
	* @param $elementid the elementid of the element for which the tag is set
	* @param $tag the tag to be set
	* @param $val the value of the tag itself
	* @param $attributes attributes to the dyntag
	* @returns void
	* @see CCMSElementValueStorage
	*/
	protected function add_dyntag($elementid, $tag, $val, $attributes)
	{
		if(is_numeric($this->tmp_newrowid))
			$this->elements->add_dyntag($elementid, $tag, $val, $attributes, $this->tmp_newrowid);
	}


	/**
	* Check if moverow is used
	* @returns true or false
	* @see CCMSTableServer::$moverow
	*/
	public function uses_moverow(){return $this->moverow;}

	
	/**
	* Deletes a rowid.
	* @param $rowid the rowid (tableid, not databaseid) to be deleted
	* @returns void
	* @see CCMSTableServer::set_rowids() for further information on rowids	
	*/
	public function del_rowid($rowid){$this->rowstorage->del_rowid($rowid);}
	

	/**
	* Get corresponding databaseid for a rowid.
	* @param $rownr the rowid
	* @returns the databaseid
	* @see CCMSRowIDStorage
	*/
	public function get_dbid($rownr){return $this->rowstorage->get_dbid($rownr);}

	/**
	* Sets a new set of tableid<->databaseid matches
	* @param $rownr the tableid
	* @param $id the datasbaseid
	* @see CCMSRowIDStorage
	* @returns void
	*/
	public function add_rowid($rownr, $id){ $this->rowstorage->add_rowid($id, $rownr); }

	/**
	* The main print function.
	* Prints either a tablecontainer with its editmodeboxes or the divs with the appropriate values
	* @param $show editmode or showmode with divs
	* @param &$vals the current values
	* @param $rownr the rownr to be shown (used for dyntags)
	* @returns void
	*/
	public function showme($show, &$vals, $rownr)
	{
		if($show == true){
			$j=0;
			while( ($curelements = $this->tablestorage->get_cellelements_numbered($j)) !== false){
				$this->generic_container($this->write_server($vals, true, $rownr, $curelements), 't'.$j, true);
				$j++;
			}
		}else{	
			echo parent::write_server($vals, false, $rownr);
		}
	}


	/**
	* Prints the delete javascript.
	* @param $rowid the rowid to be deleted
	* @returns void
	* @see table.php
	*/
	public function get_deljs($rowid)
	{
		?>
		<script type="text/javascript">
			/* <![CDATA[ */
			window.parent.del_tr_<?php echo $this->objectname;?>_iframe(<?php echo $rowid;?>);
			/* ]]> */
		</script>
		<?php
	}

	/**
	* Prints the javascript to add a new datarow.
	* @param $nwrow the tableid for the new row
	* @returns void
	* @see table.php
	*/
	public function get_addjs($nwrow)
	{
		$this->write_newtablecontainer($nwrow);

		//write the javascript to transfer the divs to the parent window...
		?>
		<script type="text/javascript">
			/* <![CDATA[ */
			window.parent.add_tr_<?php echo $this->objectname;?>_iframe(document.getElementById('ccms_tmptable') ,'<?php echo $nwrow;?>');
			/* ]]> */
		</script>
	
		<?php
		
	}

	/**
	* Writes a complete tablecontainer with no values.
	* @param $nr the tableid
	* @returns void
	* @see write_tbl()
	*/
	
	protected function write_newtablecontainer($nr)
	{
		echo "<div id=\"ccms_tmptable\"><table>";
		$cur_divnr = 0;
		
		$allrows = $this->tablestorage->get_structarray();
		$empty = array();
		
		for($i=0;$i<count($allrows);$i++){
			
			echo "<tr id=\"ccms_".$this->objectname."_".$nr."_".$i."_tr\" ".$allrows[$i].">\n";
				$allcols = $this->tablestorage->get_structarray($i);
				for($j=0;$j<count($allcols);$j++){
					echo "<td ".$allcols[$j].">\n";
						$allelements = $this->tablestorage->get_cellelements($i, $j);
						if(count($allelements) == 0)
							continue;
						$namepre = 'ccms_'.$this->objectname.'_t_'.$nr.'_';
						//write the visible div (do so always)
						$this->generic_container($this->write_engine($nr, true, $namepre, $allelements), 
											$this->objectname.'_'.$nr.'_'.$cur_divnr, true);
						
						$this->generic_container($this->write_engine($nr, false, $namepre, $allelements), 
											$this->objectname.'_'.$nr.'_'.$cur_divnr, false);
						$cur_divnr++;
					echo "\n</td>\n";
				}
			if($i==0){
				echo "\n<td rowspan=\"".count($allrows)."\" id=\"ccms_".$this->objectname."_".$nr."_fieldset\" style=\"vertical-align:top;\">\n";
				echo "<a href=\"javascript:switch_boxes_".$this->objectname."('".$nr."')\" class=\"ccms_headeditlink\">edit</a><br />\n";
				echo "<a href=\"javascript:del_tr_".$this->objectname."('".$nr."')\" class=\"ccms_headeditlink\">delete</a>\n";
				echo "</td>\n";
			}
			echo "</tr>\n\n";
		}
		echo "</table></div>";
	}


	/**
	* Calls the movejs to sort in the updated record.
	* As for the movetypes, there are 4:<BR>
	* 0-> insert at the end <BR> 1 -> insert before <BR> 2 -> insert after <BR> 3 -> insert at the top<BR>
	* @param $moveid the tableid to be moved
	* @param $movepointdbid the rowid used as the point to move it to
	* @param $movetype where to move it exactly. See details.
	* @returns void
	*/
	public function get_movejs($moveid, $movepointdbid, $movetype)
	{
		$movepoint = $this->rowstorage->get_tblid($movepointdbid);
		?>
		//if needed, move the tr to a specific position
		window.parent.move_tr('<?php echo $moveid;?>', '<?php echo $movepoint;?>', <?php echo $movetype;?>);
		<?php
	}

	/**
	* The javascript to move values from the iframe to the GUI
	* Moves the showmode divs and the elements' values
	* @param $rowid the tablerowid to be moved to in the GUI
	* @returns void
	*/
	public function get_editjs($rowid)
	{
		?>
		//update a specific td
		function update_div(rowid, iframeid)
		{			
			tblvis = window.parent.document.getElementById('ccms_<?php echo $this->objectname;?>_'+rowid+'_'+iframeid+'_vis');
			cur_settext = document.getElementById('ccms_t' + iframeid + '_vis').innerHTML;
			tblvis.innerHTML = cur_settext;

		}
		//update a specific element
		function update_element(rowid, elementnr)
		{
			edittext = document.getElementsByName('ccms_t' + elementnr)[0];
			parel = window.parent.document.getElementsByName('ccms_<?php echo $this->objectname;?>_t_'+rowid+'_'+elementnr)[0];
			ccms_copy(edittext, parel);
		}
		

		function update_all_el(rowid)
		{
			var i=0;
			while(document.getElementsByName('ccms_t' + i)[0]){
				update_element(rowid, i);
				i++;
			}
		}


		function update_all_div(rowid)
		{
			var i=0;
			while(document.getElementById('ccms_t' + i + '_vis')){
				update_div(rowid, i);
				i++;
			}
		}



		update_all_el(<?php echo $rowid;?>);
		update_all_div(<?php echo $rowid;?>);		
		
		

		//switch the parent back to view mode
		window.parent.switch_boxes_<?php echo $this->objectname;?>(<?php echo $rowid;?>);
		<?php
	
	}

	
	

}
/*! @} */

?>
