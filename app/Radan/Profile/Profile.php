<?php

/**
 * This file is part of Radan Profile, 
 *
 * This class provide common functionality to work with user profile radan package
 *
 * @package    radan/profile 
 * @license    MIT
 * 
 */

namespace App\Radan\Profile;

use Validator;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;
use App\Radan\Profile\ProfileStructure;
use App\Radan\Profile\Models\Profile as ProfileModel;
use App\Radan\Profile\Models\ProfileUser as ProfileUserModel;

/**
 * Profile is a manager for user profile.
 *
 * @author Mehdi Riahimanesh <m.riahimanesh@radanit.ir>
 */

class Profile
{
    /**
     * The storage define in service provider          
     *
     * @var Storage
     */
    protected $disk;    

    /**
     * The storage define in service provider          
     *
     * @var ProfileModel
     */
    protected $profile;

    /**
     * For support profile data bag,     
     *
     * @var boolean
     */
    protected $isDataBag = false;

    /**
     * For support profile data bag,
     * Name of profile data field
     *
     * @var string
     */
    protected $dataBagFieldName = 'profile_data';    

    /**
     * Class constructor
     *
     * @param  FileSystem  $disk Laravel FileSystem disk
     * @param  boolean $isDataBag Set to true if profile data store in bag
     * @return Profile
     */
    public function __construct(FileSystem $disk,$isDataBag = false)
    {
        if (is_null($disk)) {
            $disk = \Storage::disk('profile_disk');
        }
        $this->disk = $disk;
        $this->isDataBag = $isDataBag;
        return $this;
    }
        
    /**
     * Return this profile instance
     * 
     * @return ProfileModel
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set profile disk for store file base profile data
     *
     * @param  FileSystem  $disk Laravel FileSystem disk
     * @return Profile
     */
    public function setDisk(FileSystem $disk) 
    {
        $this->disk=$disk;
        return $this;
    }

    /**
     * Get profile disk for store file base profile data
     *    
     * @return FileSystem Laravel FileSystem disk
     */
    public function getDisk()
    {        
        return $this->disk;
    }

    /**
     * Get profile table name
     *    
     * @return string
     */
    public function getTable() 
    {
        return ProfileModel::getTableName();
    }
    
    /**
     * Set profile data bag name
     *
     * @param  FileSystem  $disk Laravel FileSystem disk
     * @return Profile
     */
    public function setDataBag($bagName)
    {
        if (isset($bagName)) {
            $this->isDataBag = true;
            $this->dataBagFieldName = $bagName;
        }
        return $this;
    }

    /**
     * Fetch profile data from data bag
     *  
     * @param array Array of data for fetch profile data  
     * @return mixed if $data is null return data bag field name,
     *                 else return profile data from bag
     */
    public function getData(Request $request,$fields=null)
    {        
        // get profile fields        
        $profileFields = with(new ProfileStructure($this->profile))->keys($fields);		
                    
        // get profile data from request first
        $profileData = [];
        $profileData = $request->only($profileFields);                
                
        if ($this->isDataBag)         
        {           
            $profileDataBag = $request->has($this->dataBagFieldName) ? $request->get($this->dataBagFieldName) : [];            
            foreach($profileFields as $field) 
            {                        
                if (isset($profileDataBag[$field])) {
                    $profileData[$field] = $profileDataBag[$field];
                }
            }
        }        
        return $profileData;
    }
    
    /**
     * Make Profile instance by id or name
     *
     * @param  mixed  $key  profile id or name
     * @param  boolean $isDataBag Set to true if profile data store in bag
     * @return Profile
     */
    public function set($key,$isDataBag = false)
    {
        $this->profile = ProfileModel::find($key);     
        if (is_null($this->profile)) {
            $this->profile = ProfileModel::where('name',$key)->first();        
        }
        $this->isDataBag = $isDataBag;
        return $this;
    }
    
    /**
     * Validate profile data by rule field in profile structure
     *    
     * @param array $data include profile data 
     * @return mixed return this on validate or raise exception on fail
     */
    public function validate($data,$fields=null)
    {        
        // get field rules				
        $rules = with(new ProfileStructure($this->profile))->rules($fields);
        
        // run validation
        Validator::make($data ,$rules)->validate();
        
        return $this;
    }

    /**
     * Check user has profile
     * 
     * @param array $user Elequent User model   
     * @return boolean
     */
    public function has($user) 
    {
        if (is_null($user)) return false;
        return !is_null($user->profile);
    }

    /**
     * Check user has profile data
     * 
     * @param array $user Elequent User model   
     * @return boolean
     */
    public function hasData($user) 
    {
       if (self::has($user)) {
           return !is_null($user->profile->data);
       } 
       return false;
    }
    
    /**
     * Create new profile data
     * 
     * @param array $user Elequent User model   
     * @param array $data include profile data 
     * @return array return profile data was stored
     */
    public function create($user,$data)
    {
        // Get profile fields and filed types
        $fieldTypes = with(new ProfileStructure($this->profile))->type;
        $newData = [];

        foreach ($fieldTypes as $field => $type)
        {        
            if ($type == 'file' and isset($data[$field])) {
                // Upload file
                $filePath = $this->disk->putFile('',$data[$field]);                
                
                // Save changes                                   
                $newData[$field] = $this->disk->url($filePath);                
            }
            elseif (isset($data[$field])) {
                $newData[$field] = $data[$field];
            }
        }
         
        // Create new profile
        $user->profile()->create([
                'data' => $newData,
                'profile_id' => $this->profile->id
        ]);                

        return $newData;
    }

    /**
     * Save user profile data
     * 
     * @param array $user Elequent User model   
     * @param array $data include profile data 
     * @return array return profile data was stored
     */
    public function update($user,$data)
    {        
        // Get profile fields and filed types        
        $fieldTypes = with(new ProfileStructure($this->profile))->type;
        $oldProfileData = is_null($user->profile) ? []:$user->profile->data;
        $oldFilePath = [];

        foreach ($fieldTypes as $field => $type)
        {        
            if ($type == 'file') {
                if (isset($data[$field])) {
                    // Upload file
                    $filePath = $this->disk->putFile('',$data[$field]);
                    
                    // save for Delete old user files
                    $oldFilePath[] = key_exists($field,$oldProfileData) ? $oldProfileData[$field]:'';
                    
                    // Save changes                                   
                    $oldProfileData[$field] = $this->disk->url($filePath);
                }
            }
            elseif (isset($data[$field])) {
                $oldProfileData[$field] = $data[$field];
            }
        }
        
        // Update user profile data        
        $profileUser = $user->profile()->first();
        $profileUser->profile_id = $this->profile->id;
        $profileUser->data = $oldProfileData;
        $user->profile()->save($profileUser);
                
        // Delete old files
        foreach($oldFilePath as $file) {            
            $this->disk->delete(basename($file));
        }

        return $oldProfileData;
    }

    /**
     * Delete user profile data
     * 
     * @param array $user Elequent User model   
     * @return boolean
     */
    public function destroy($user) 
    {
        // Get profile fields and filed types        
        $fieldTypes = with(new ProfileStructure($user->profile->type))->type;
        $profileData = is_null($user->profile) ? []:$user->profile->data;       

        // remove files
        foreach ($fieldTypes as $field => $type)
        {        
            if ($type == 'file' && isset($profileData[$field]))
            {
                $file = $profileData[$field];
                $this->disk->delete(basename($file));
            }
        }

        // Find user_profile record by user_id relation and destroy it
        return $user->profile()->delete();
    }
}
