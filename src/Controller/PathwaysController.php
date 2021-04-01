<?php
declare(strict_types=1);

namespace App\Controller;


Use Cake\ORM\TableRegistry;
use Cake\Utility\Text;

/**
 * Pathways Controller
 *
 * @property \App\Model\Table\PathwaysTable $Pathways
 * @method \App\Model\Entity\Pathway[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PathwaysController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Topics', 'Ministries', 'Statuses'],
        ];
        $pathways = $this->paginate($this->Pathways);

        $this->set(compact('pathways'));
    }

    /**
     * View method
     *
     * @param string|null $id Pathway id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($slug = null)
    {
        //$this->Authorization->skipAuthorization();
        // As we loop through the activities for the steps on this pathway, we 
        // need to be able to check to see if the current user has "claimed" 
        // that activity. Here we get the current user id and use it to select 
        // all of the claimed activities assigned to them, and then process out 
        // just the activity IDs into a simple array. Then, in the template 
        // code, we can simply  if(in_array($rj->activity->id,$useractivitylist
        //
        // First let's check to see if this person is logged in or not.
        //
	    $user = $this->request->getAttribute('authentication')->getIdentity();
        if(!empty($user)) {
            // We need create an empty array first. If nothing gets added to
            // it, so be it
            $useractivitylist = array();
            // Get access to the apprprioate table
            $au = TableRegistry::getTableLocator()->get('ActivitiesUsers');
            // Select based on currently logged in person
            $useacts = $au->find()->where(['user_id = ' => $user->id]);
            // convert the results into a simple array so that we can
            // use in_array in the template
            $useractivities = $useacts->toList();
            // Loop through the resources and add just the ID to the 
            // array that we will pass into the template
            foreach($useractivities as $uact) {
                array_push($useractivitylist, $uact['activity_id']);
            }
        }
        $pathway = $this->Pathways->findBySlug($slug)->contain([
                            'Topics',
                            'Topics.Categories', 
                            'Ministries', 
                            'Steps', 
                            'Steps.Activities', 
                            'Steps.Activities.ActivityTypes', 
                            'Users'])->firstOrFail();
        //
        // we want to be able to tell if the current user is already on this
        // pathway or not, so we take the same approach as above, parsing all
        // the users into a single array so that we can perform a simple
        // in_array($thisuser,$usersonthispathway) check and show the "take
        // this Pathway" button or "you're on this Pathway" text
        //
        // Create the initially empty array that we also pass into the template
        $usersonthispathway = array();
        $followers = array();
        // Loop through the users that are on this pathway and parse just the 
        // IDs into the array that we just created
        foreach($pathway->users as $pu) {
            array_push($usersonthispathway,$pu->id);
            array_push($followers,[$pu->id,$pu->name]);
        }

        // In order to implement the scrollspy step navigation we zip through
        // and compile a list of the steps and convert them to slugs. Now we
        // can run through the steps and link to them outside of the main 
        // loop #TODO in the template we're hacking this by having a separate
        // slugify function because we don't yet store the slug when we save
        // the step. We need to add a new entity to the steps table (also the
        // pathways table) to do this. Fairly high priority really.
        $stepsalongtheway = array();
        foreach($pathway->steps as $step) {
            array_push($stepsalongtheway,array('slug' => Text::slug(strtolower($step->name)), 
                                                'name' => $step->name, 
                                                'objective' => $step->description));
        }

        $this->set(compact('pathway', 'usersonthispathway','stepsalongtheway', 'useractivitylist','followers'));

    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $pathway = $this->Pathways->newEmptyEntity();
        if ($this->request->is('post')) {
            $pathway = $this->Pathways->patchEntity($pathway, $this->request->getData());
            if ($this->Pathways->save($pathway)) {
                $this->Flash->success(__('The pathway has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The pathway could not be saved. Please, try again.'));
        }
        $topics = $this->Pathways->Topics->find('list', ['limit' => 200]);
        $ministries = $this->Pathways->Ministries->find('list', ['limit' => 200]);
        $statuses = $this->Pathways->Statuses->find('list', ['limit' => 200]);
        $competencies = $this->Pathways->Competencies->find('list', ['limit' => 200]);
        $steps = $this->Pathways->Steps->find('list', ['limit' => 200]);
        $users = $this->Pathways->Users->find('list', ['limit' => 200]);
        $this->set(compact('pathway', 'topics', 'ministries', 'statuses', 'competencies', 'steps', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Pathway id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $pathway = $this->Pathways->get($id, [
            'contain' => ['Competencies', 'Steps', 'Users'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pathway = $this->Pathways->patchEntity($pathway, $this->request->getData());
            if ($this->Pathways->save($pathway)) {
                $this->Flash->success(__('The pathway has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The pathway could not be saved. Please, try again.'));
        }
        $topics = $this->Pathways->Topics->find('list', ['limit' => 200]);
        $ministries = $this->Pathways->Ministries->find('list', ['limit' => 200]);
        $statuses = $this->Pathways->Statuses->find('list', ['limit' => 200]);
        $competencies = $this->Pathways->Competencies->find('list', ['limit' => 200]);
        $steps = $this->Pathways->Steps->find('list', ['limit' => 200]);
        $users = $this->Pathways->Users->find('list', ['limit' => 200]);
        $this->set(compact('pathway', 'topics', 'ministries', 'statuses', 'competencies', 'steps', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Pathway id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pathway = $this->Pathways->get($id);
        if ($this->Pathways->delete($pathway)) {
            $this->Flash->success(__('The pathway has been deleted.'));
        } else {
            $this->Flash->error(__('The pathway could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
