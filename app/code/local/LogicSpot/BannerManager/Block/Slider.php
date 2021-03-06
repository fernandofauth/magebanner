<?php   
class LogicSpot_BannerManager_Block_Slider extends Mage_Core_Block_Template
{
	private $_bannerGroupData = null;
	
	public function getCollection()
	{
		$collection = Mage::getModel('bannermanager/banner')->getCollection();
		$bannerGroup = $this->getBannerGroupData();
		
		if(!$bannerGroup)
		{
			return false;
		}
		
		$today = date('Y-m-d H:i:s');
		
		if($bannerGroup->getSortType() == 1)
		{
			$collection->setRandomOrder();
		}else{
			$collection->setOrder('priority', 'ASC');
		}
		
		$collection->addFieldToFilter('date_from', array(
			array('to' => $today),
			array('null' => true),
		));
		
		$collection->addFieldToFilter('date_to', array(
			array('from' => $today),
			array('null' => true),
		));
		
		$collection->addFieldToFilter('group_id', array(
			'finset' => $this->getBannerGroupId()
		));
		
		$collection->addFieldToFilter('store_view', array(
			array('finset' => 0),
			array('finset' => 1),
		));
		
		if($bannerGroup->getMaxBanners())
		{
			$collection->setPageSize($bannerGroup->getMaxBanners());
		}
		
		return $collection;
	}
	
	public function setBannerGroupData($bannerGroupId = null)
	{
		if($bannerGroupId)
		{
			$bannerGroups = Mage::getModel('bannermanager/group')->getCollection();
			$bannerGroups->addFieldToFilter('identifier',$bannerGroupId)->setPageSize(1);
			if(count($bannerGroups))
			{
				foreach($bannerGroups as $bannerGroup)
				{
					$this->_bannerGroupData = $bannerGroup;
				}
				return $this->getBannerGroupData();
			}
		}
		return false;
	}
	
	public function getBannerGroupData()
	{
		return $this->_bannerGroupData;
	}
	
	public function getBannerGroupId()
	{
		$groupData = $this->getBannerGroupData();
		return $groupData->getGroupId();
	}
}