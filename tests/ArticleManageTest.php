<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Minhbang\User\User;

class ArticleManageTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * @var array
     */
    protected $users = [];

    public function setUp()
    {
        parent::setUp();
        $this->users['user'] = factory(User::class)->create();
        $this->users['admin'] = factory(User::class, 'sys.admin')->create();
        $this->users['super_admin'] = factory(User::class, 'sys.sadmin')->create();
    }


    /**
     * Yêu cầu đăng nhập khi truy cập
     */
    public function testLoginToManagementPage()
    {
        $this->visit('/backend/article')
            ->seePageIs('/auth/login');
    }

    /**
     * Không có quyền truy cập
     */
    public function testUserAccessManagementPage()
    {
        $this->actingAs($this->users['user'])->get('/backend/article')
            ->assertResponseStatus(403);
    }

    /**
     * Truy cập thành công
     */
    public function testAdminAccessManagementPage()
    {
        $this->actingAs($this->users['admin'])->get('/backend/article')
            ->assertResponseOk();
    }

    /**
     * Truy cập bằng quyền Super Admin
     */
    public function testSuperAdminAccessManagementPage()
    {
        $this->actingAs($this->users['super_admin'])->visit('/backend/article')
            ->see(__('Manage'));
    }
}