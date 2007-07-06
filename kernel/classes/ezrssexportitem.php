<?php
//
// Definition of eZRSSExportItem class
//
// Created on: <18-Sep-2003 13:13:56 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file ezrssexportitem.php
*/

/*!
  \class eZRSSExportItem ezrssexportitem.php
  \brief Handles RSS Export Item in eZ publish

  RSSExportItem is used to create RSS feeds from published content. See kernel/rss for more files.
*/

include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( 'kernel/classes/ezrssexport.php' );

class eZRSSExportItem extends eZPersistentObject
{

    /*!
     Initializes a new RSSExportItem.
    */
    function eZRSSExportItem( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \reimp
    */
    function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'rssexport_id' => array( 'name' => 'RSSExportID',
                                                                  'datatype' => 'integer',
                                                                  'default' => '',
                                                                  'required' => true ),
                                         'source_node_id' => array( 'name' => 'SourceNodeID',
                                                                    'datatype' => 'integer',
                                                                    'default' => '',
                                                                    'required' => true ),
                                         'class_id' => array( 'name' => 'ClassID',
                                                              'datatype' => 'integer',
                                                              'default' => '',
                                                              'required' => true ),
                                         'description' => array( 'name' => 'Description',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         'title' => array( 'name' => 'Title',
                                                           'datatype' => 'string',
                                                           'default' => '',
                                                           'required' => true ),
                                         'status' => array( 'name' => 'Status',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         'subnodes' => array( 'name' => 'Subnodes',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ) ),
                      "keys" => array( "id", 'status' ),
                      'function_attributes' => array( 'class_attributes' => 'classAttributes',
                                                      'source_node' => 'sourceNode',
                                                      'source_path' => 'sourcePath' ),
                      "increment_key" => "id",
                      "class_name" => "eZRSSExportItem",
                      "name" => "ezrss_export_item" );
    }

    /*!
     \static
     Creates a new RSS Export Item
     \param EZRSSExport objcted id. (The RSSExport this item belongs to)

     \return the URL alias object
    */
    function create( $rssexport_id )
    {
        $row = array( 'id' => null,
                      'rssexport_id' => $rssexport_id,
                      'source_node_id' => 0,
                      'class_id' => 1,
                      'url_id' => '',
                      'description' => '',
                      'title' => '',
                      'status' => 0,
                      'subnodes' => 0);
        return new eZRSSExportItem( $row );
    }

    function &classAttributes()
    {
        if ( isset( $this->ClassID ) and $this->ClassID )
        {
            include_once( 'kernel/classes/ezcontentclass.php' );
            $contentClass = eZContentClass::fetch( $this->ClassID );
            if ( $contentClass )
                $attributes =& $contentClass->fetchAttributes();
            else
                $attributes = null;
        }
        else
            $attributes = null;
        return $attributes;
    }

    function &sourcePath()
    {
        $retValue = null;
        if ( isset( $this->SourceNodeID ) and $this->SourceNodeID )
        {
            include_once( "kernel/classes/ezcontentobjecttreenode.php" );
            $objectNode = eZContentObjectTreeNode::fetch( $this->SourceNodeID );
            if ( isset( $objectNode ) )
            {
                $path_array = $objectNode->attribute( 'path_array' );
                $path_array_count = count( $path_array );
                for ( $i = 0; $i < $path_array_count; ++$i )
                {
                    $treenode = eZContentObjectTreeNode::fetch( $path_array[$i] );
                    if ( is_array( $treenode ) && array_key_exists( 'name', $treenode ) )
                    {
                        if ( $i == 0 )
                        {
                            $retValue = $treenode['name'];
                        }
                        else
                        {
                            $retValue .= '/' . $treenode['name'];
                        }
                    }
                }
            }
        }
        return $retValue;
    }

    function &sourceNode()
    {
        if ( isset( $this->SourceNodeID ) and $this->SourceNodeID )
        {
            include_once( "kernel/classes/ezcontentobjecttreenode.php" );
            $sourceNode = eZContentObjectTreeNode::fetch( $this->SourceNodeID );
        }
        else
            $sourceNode = null;
        return $sourceNode;
    }

    /*!
     \static
      Fetches the RSS Export by ID.

     \param RSS Export ID
    */
    function fetch( $id, $asObject = true, $status = EZ_RSSEXPORT_STATUS_VALID )
    {
        return eZPersistentObject::fetchObject( eZRSSExportItem::definition(),
                                                null,
                                                array( "id" => $id,
                                                       'status' => $status ),
                                                $asObject );
    }

    /*
     Fetches the items belonging to the specified RSSExport
     example: fetchFilteredList( array( 'rssexport_id' => 24 ) )

     \param array, example: array( 'rssexport_id' => 24 )

     \return array containing RSSExport Items
    */
    function fetchFilteredList( $cond, $asObject = true, $status = EZ_RSSEXPORT_STATUS_VALID )
    {
        return eZPersistentObject::fetchObjectList( eZRSSExportItem::definition(),
                                                    null, $cond, array( 'id' => 'asc',
                                                                        'status' => $status ), null,
                                                    $asObject );
    }

    function getAttributeMappings( $rssSources )
    {
        if( is_array( $rssSources ) && count( $rssSources ) )
        {
            foreach( $rssSources as $rssSource )
            {
                // fetch path for class attribute to RSS field mapping
                $node = eZContentObjectTreeNode::fetch( $rssSource->SourceNodeID );
                if ( is_object( $node ) )
                {
                    $attributeMappings[] = array( $rssSource, $node );
                }
            }

            // sort the array so nodes with deeper path are first
            // for class attribute to RSS field mapping
            usort( $attributeMappings,
                   create_function( '$a, $b',
                                    '$a_cnt = count( $a[1]->attribute( \'path_array\' ) );' .
                                    '$b_cnt = count( $b[1]->attribute( \'path_array\' ) );' .
                                    'return ( $a_cnt == $b_cnt ) ? 0 : ( ( $a_cnt > $b_cnt ) ? 1 : -1 );' ) );
        }

        return $attributeMappings;
    }

    /*!
     Get the N last published nodes matching the specifications of this RSS Export item

     \param number of objects to fetch

     \return list of Nodes
    */
    function fetchNodeList( $rssSources, $objectListFilter )
    {
        // compose parameters for several subtrees
        if( is_array( $rssSources ) && count( $rssSources ) )
        {
            foreach( $rssSources as $rssSource )
            {
                // Do not include subnodes
                if ( !intval( $rssSource->Subnodes ) )
                {
                    $depth = 1;
                }
                else // Fetch objects even from subnodes
                {
                    $depth = 0;
                }

                $nodesParams[] = array( 'ParentNodeID' => $rssSource->SourceNodeID,
                                        'ResultID' => $rssSource->ID,
                                        'Depth' => $depth,
                                        'DepthOperator' => 'eq',
                                        'MainNodeOnly' => $objectListFilter['main_node_only'],
                                        'ClassFilterType' => 'include',
                                        'ClassFilterArray' => array( intval( $rssSource->ClassID ) )
                                       );
            }

            $listParams = array( 'Limit' => $objectListFilter['number_of_objects'],
                                 'SortBy' => array( 'published', false )
                                );

            include_once( "kernel/classes/ezcontentobjecttreenode.php" );
            $nodeList = eZContentObjectTreeNode::subTreeMultiPaths( $nodesParams, $listParams );
        }
        else
            $nodeList = null;
        return $nodeList;
    }

}

?>
