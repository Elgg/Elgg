<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | TemplatePower Module => TPLSerializer:                               |
// | offers you the ability save parsed templates to disk                 |
// +----------------------------------------------------------------------+
// |                                                                      |
// | Copyright (C) 2001  R.P.J. Velzeboer, The Netherlands                |
// |                                                                      |
// | This program is free software; you can redistribute it and/or        |
// | modify it under the terms of the GNU General Public License          |
// | as published by the Free Software Foundation; either version 2       |
// | of the License, or (at your option) any later version.               |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to the Free Software          |
// | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA            |
// | 02111-1307, USA.                                                     |
// |                                                                      |
// | Author: R.P.J. Velzeboer, rovel@codocad.nl   The Netherlands         |
// |                                                                      |
// +----------------------------------------------------------------------+
// | http://templatepower.codocad.com                                     |
// +----------------------------------------------------------------------+
//
// $Id: mod.TPLSerializer.inc.php,v 1.1 2003/12/28 19:21:07 micd Exp $

include("./class.TemplatePower.inc.php");

class TPLSerializer extends TemplatePowerParser
{
  var $stpl_file;

  /**********
      constructor
            ***********/

    function TPLSerializer( $tpl_file, $stpl_file )
    {
        $this->stpl_file = $stpl_file;

        TemplatePowerParser::TemplatePowerParser( $tpl_file, T_BYFILE );
    }

  /**********
      private members
            ***********/

    function __serializeTPL()
    {
        $fp = @fopen( $this->stpl_file, "w")  or die( $this->__errorAlert('TemplatePower Error: Couldn\'t write [ '. $this->stpl_file  .'] for serializing!') );

        $stuffToSerialize = Array( defBlock => $this->defBlock, index => $this->index, parent => $this->parent );

        fputs(  $fp, serialize($stuffToSerialize) );
        fclose( $fp );
        chmod(  $this->stpl_file, 0777 );
    }

  /**********
      public members
            ***********/

    function doSerialize()
    {
        TemplatePowerParser::__prepare();
        $this->__serializeTPL();
    }
}
?>