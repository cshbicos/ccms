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
* @file CCMSListServer.class.php
* Server part of the CCMS List Object.
*/

/**
* The serverpart for the CCMS List Object.
* @see list.php on how this class is used
*/

abstract class CCMSListServer extends CCMSGenericServer
{
	/**
	* Adds a new entry filled with standart values.
	* Real values will be filled in via edit. Makes sense if you consider the user only clicks the "add" link to create a new row.<BR>
	* You can also add "standard" values to the newly created row in here. Just add them as done in the GUI object.<BR>
	* @param $lvl the indent level of the added value
	* @returns the id of the new row
	* @see list.php
	*/
	abstract public function add_new($lvl);

  	/**
	* Takes care of deleting one data row.
	* The database id will be handed over via id. This function only needs to take care of deleting it.<BR>
	* BEWARE!!!!!! This will not delete any subentries of the list, although it will not show them anymore (until reload)<BR>
	* It is up to you to delete them!!!
	* @param $id the databaseid
	* @returns void
	* @see list.php
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
	* @param $lvl the indent level
	* @returns void
	* @see list.php
	*/
	abstract public function update_me(&$vars, $lvl);

	/**
	* Liststorage.
	* @see See CCMSListStorage for further information
	*/
	protected $liststorage;

	/**
	* Rowid storage.
	* @see See CCMSRowIDStorage for further information
	*/
	protected $rowstorage;


	/**
	* Temporary storage for a newly created rowid.
	* Makes the add_tmp_val() and add_dyntag() function work
	* @see CCMSListServer::add_tmp_val(), CCMSListServer::add_dyntag()
	*/
	protected $tmp_newrowid;

	/**
	* Magic function for serialize().
	* Returns all variables to be saved when serializing.
	*/
	public function __sleep()
	{
		return array_merge ( parent::__sleep(), array('liststorage', 'rowstorage'));
	}

	/**
	* The constructor.
	* @param $valstor the serialized CCMSElementValueStorage Object, the same as created for the GUI
	* @param $liststor the serialized CCMSListStorage element
	* @param $rowids serialized CCMSRowIDStorage
	* @returns void
	*/
	function __construct($valstor, $liststor, $rowids)
	{
		parent::__construct($valstor);
		$this->liststorage = unserialize($liststor);
		$this->rowstorage = unserialize($rowids);
	}

	/**
	* Sets the $tmp_newrowid variable.
	* @param $val the value to set it to.
	* @returns void
	* @see CCMSListServer::$tmp_newrowid
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
	* Deletes a rowid.
	* @param $rowid the rowid to be deleted
	* @returns void
	* @see CCMSRowIDStorage
	*/
	public function del_rowid($rowid){ $this->rowstorage->del_rowid($rowid); }
				
			
	/**
	* Get corresponding databaseid for a rowid.
	* @param $rownr the rowid
	* @returns the databaseid
	* @see CCMSRowIDStorage
	*/
	function get_dbid($rownr){return $this->rowstorage->get_dbid($rownr);}

	/**
	* Sets a new set of tableid<->databaseid matches
	* @param $rownr the tableid
	* @param $id the datasbaseid
	* @returns void
	* @see CCMSTableServer::set_rowids() for further information on rowids
	*/
	public function add_rowid($rownr, $id){ $this->rowstorage->add_rowid($id, $rownr); }


	/**
	* Get the level for a certain startelement.
	* @param $startelement what elementid it starts with...
	* @returns the indent level
	*/
	public function get_level($startelement)
	{
		return $this->liststorage->get_level($startelement);
	}

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
		$tmp = $vals['startelement'];
		if($show == true){
			$curelements = $this->liststorage->get_elements($this->liststorage->get_level($tmp));
			$this->generic_container($this->write_server($vals, true, $rownr, $curelements), 't', true);
			
		}else{	
			echo parent::write_server($vals, false, $rownr);
		}
		$vals['startelement'] =$tmp;
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
			window.parent.del_li_<?php echo $this->objectname;?>_iframe(<?php echo $rowid;?>);
			/* ]]> */
		</script>
		<?php
	}

	/**
	* Prints the javascript to add a new datarow.
	* @param $nwrow the rowid for the new row
	* @param $subto sub to what ul/ol the new entry is to be insert
	* @param $lvl the level of the new insert li (used to determin if an "add" sub is needed)
	* @returns void
	* @see list.php
	*/
	public function get_addjs($nwrow, $subto, $lvl)
	{
		$this->write_newcontainer($nwrow, $lvl);

		//write the javascript to transfer the divs to the parent window...
		?>
		<script type="text/javascript">
			/* <![CDATA[ */
			window.parent.add_li_<?php echo $this->objectname;?>_iframe('<?php echo $subto;?>', '<?php echo $nwrow;?>', '<?php echo $this->liststorage->get_elementcountstart($lvl);?>');	
			/* ]]> */
		</script>
		
		<?php
		
	}

	/**
	* Writes a complete li as it will apear later on.
	* @param $nr the rowid
	* @param $lvl the level of the new insert li (used to determin if an "add" sub is needed)
	* @returns void
	*/
	
	protected function write_newcontainer($nr, $lvl)
	{
		echo "<ol id=\"ccms_preview_list\"><li ".$this->liststorage->get_settings($lvl, 1).">";
		
		$allelements = $this->liststorage->get_elements($lvl);
	 
		$namepre = 'ccms_'.$this->objectname."_t_".$nr."_";
		$this->generic_container($this->write_engine($nr, true, $namepre, $allelements), $this->objectname."_".$nr, true);
		$this->generic_container($this->write_engine($nr, false, $namepre, $allelements), $this->objectname."_".$nr, false);
	
		echo "&nbsp;<span id=\"ccms_".$this->objectname."_".$nr."_fieldset\">";
		echo "<a href=\"javascript:switch_boxes_".$this->objectname."('".$nr."', '".$this->liststorage->get_elementcountstart($lvl)."')\" class=\"ccms_headeditlink\">edit</a>&nbsp;";
		echo "<a href=\"javascript:del_li_".$this->objectname."('".$nr."')\" class=\"ccms_headeditlink\">delete</a></span>";

		//if the current level isn't the deepest, add another add new below the currently created element...
		if($lvl < ($this->liststorage->get_settings(0, 2)-1)){
			list($tag, $attri, $alllvls) = $this->liststorage->get_settings(($lvl+1), 0);
			echo "<". $tag . " id=\"ccms_".$this->objectname."_l_".$nr."\" ".$attri.">\n";
			echo "<li><a href=\"javascript:add_li_".$this->objectname."('".$nr."', '".($lvl+1)."')\" class=\"ccms_headeditlink\">Add new</a></li>\n";
			echo "</".$tag.">";
		}

		echo "</li></ol>";
	}

	/**
	* The javascript to move values from the iframe to the GUI
	* Moves the showmode divs and the elements' values
	* @param $rowid the rowid to be moved to in the GUI
	* @returns void
	*/
	public function get_editjs($rowid)
	{
		?>
		//update spans
		parvis = window.parent.document.getElementById('ccms_<?php echo $this->objectname;?>_<?php echo $rowid;?>_vis');
		cur_settext = document.getElementById('ccms_t_vis').innerHTML;
		parvis.innerHTML = cur_settext;


		//update all elements
		pardoc = window.parent.document;
		var i=document.getElementsByName("startelement")[0].value;
		while(pardoc.getElementsByName('ccms_<?php echo $this->objectname;?>_t_<?php echo $rowid;?>_'+i)[0]){
			ccms_copy(document.getElementsByName('ccms_t' + i)[0], pardoc.getElementsByName('ccms_<?php echo $this->objectname;?>_t_<?php echo $rowid;?>_'+i)[0]);
			i++;
		}

		//switch the parent back to view mode
		window.parent.switch_boxes_<?php echo $this->objectname;?>(<?php echo $rowid;?>, document.getElementsByName("startelement")[0].value);
		<?php
	
	}

	
	

}
/*! @} */

?>
