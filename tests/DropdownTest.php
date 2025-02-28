<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Bootstrap5\Tests;

use RuntimeException;
use Yiisoft\Yii\Bootstrap5\Dropdown;

/**
 * Tests for Dropdown widget.
 *
 * DropdownTest.
 */
final class DropdownTest extends TestCase
{
    public function testRender(): void
    {
        Dropdown::counter(0);

        $html = Dropdown::widget()
            ->items([
                [
                    'label' => 'Page1',
                    'url' => '#',
                    'disabled' => true,
                ],
                [
                    'label' => 'Page2',
                    'url' => '#',
                    'active' => true,
                ],
                [
                    'label' => 'Dropdown1',
                    'url' => '#test',
                    'items' => [
                        ['label' => 'Page2'],
                        ['label' => 'Page3'],
                    ],
                ],
                [
                    'label' => 'Dropdown2',
                    'visible' => false,
                    'items' => [
                        ['label' => 'Page4', 'content' => 'Page4'],
                        ['label' => 'Page5', 'content' => 'Page5'],
                    ],
                ],
            ])
            ->render();
        $expected = <<<'HTML'
        <ul id="w0-dropdown" class="dropdown-menu" aria-expanded="false">
        <li><a class="dropdown-item disabled" href="#" tabindex="-1" aria-disabled="true">Page1</a></li>
        <li><a class="dropdown-item active" href="#">Page2</a></li>
        <li class="dropdown" aria-expanded="false"><a class="dropdown-item dropdown-toggle" href="#test" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false" role="button">Dropdown1</a><ul id="w1-dropdown" class="dropdown-menu" aria-expanded="false">
        <li><h6 class="dropdown-header">Page2</h6></li>
        <li><h6 class="dropdown-header">Page3</h6></li>
        </ul></li>
        </ul>
        HTML;
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testMissingLabel(): void
    {
        $this->expectException(RuntimeException::class);
        Dropdown::widget()
            ->items([['url' => '#test']])
            ->render();
    }

    public function testSubMenuOptions(): void
    {
        Dropdown::counter(0);

        $html = Dropdown::widget()
            ->items([
                [
                    'label' => 'Dropdown1',
                    'items' => [
                        ['label' => 'Page1', 'content' => 'Page2'],
                        ['label' => 'Page2', 'content' => 'Page3'],
                    ],
                ],
                '-',
                [
                    'label' => 'Dropdown2',
                    'items' => [
                        ['label' => 'Page3', 'content' => 'Page4'],
                        ['label' => 'Page4', 'content' => 'Page5'],
                    ],
                    'submenuOptions' => [
                        'class' => 'submenu-override',
                    ],
                ],
            ])
            ->submenuOptions(['class' => 'submenu-list'])
            ->render();
        $expected = <<<'HTML'
        <ul id="w0-dropdown" class="dropdown-menu" aria-expanded="false">
        <li class="dropdown" aria-expanded="false"><a class="dropdown-item dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false" role="button">Dropdown1</a><ul id="w1-dropdown" class="submenu-list dropdown-menu" aria-expanded="false">
        <li><h6 class="dropdown-header">Page1</h6></li>
        <li><h6 class="dropdown-header">Page2</h6></li>
        </ul></li>
        <li><hr class="dropdown-divider"></li>
        <li class="dropdown" aria-expanded="false"><a class="dropdown-item dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false" role="button">Dropdown2</a><ul id="w2-dropdown" class="submenu-override dropdown-menu" aria-expanded="false">
        <li><h6 class="dropdown-header">Page3</h6></li>
        <li><h6 class="dropdown-header">Page4</h6></li>
        </ul></li>
        </ul>
        HTML;
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testForms(): void
    {
        Dropdown::counter(0);

        $form = <<<'HTML'
        <form class="px-4 py-3">
        <div class="form-group">
        <label for="exampleDropdownFormEmail1">Email address</label>
        <input type="email" class="form-control" id="exampleDropdownFormEmail1" placeholder="email@example.com">
        </div>
        <div class="form-group">
        <label for="exampleDropdownFormPassword1">Password</label>
        <input type="password" class="form-control" id="exampleDropdownFormPassword1" placeholder="Password">
        </div>
        <div class="form-check">
        <input type="checkbox" class="form-check-input" id="dropdownCheck">
        <label class="form-check-label" for="dropdownCheck">
        Remember me
        </label>
        </div>
        <button type="submit" class="btn btn-primary">Sign in</button>
        </form>
        HTML;
        $html = Dropdown::widget()
            ->items([
                $form,
                '-',
                ['label' => 'New around here? Sign up', 'url' => '#'],
                ['label' => '-'],
                ['label' => 'Forgot password?', 'url' => '#'],
                ['label' => '-', 'visible' => false],
            ])
            ->render();
        $expected = <<<'HTML'
        <ul id="w0-dropdown" class="dropdown-menu" aria-expanded="false">
        <li><form class="px-4 py-3">
        <div class="form-group">
        <label for="exampleDropdownFormEmail1">Email address</label>
        <input type="email" class="form-control" id="exampleDropdownFormEmail1" placeholder="email@example.com">
        </div>
        <div class="form-group">
        <label for="exampleDropdownFormPassword1">Password</label>
        <input type="password" class="form-control" id="exampleDropdownFormPassword1" placeholder="Password">
        </div>
        <div class="form-check">
        <input type="checkbox" class="form-check-input" id="dropdownCheck">
        <label class="form-check-label" for="dropdownCheck">
        Remember me
        </label>
        </div>
        <button type="submit" class="btn btn-primary">Sign in</button>
        </form></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#">New around here? Sign up</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#">Forgot password?</a></li>
        </ul>
        HTML;
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testEncodeLabels(): void
    {
        Dropdown::counter(0);

        $html = Dropdown::widget()
            ->items([
                [
                    'label' => '<span><i class=fas fastest></i>Dropdown1</span>',
                    'items' => [
                        ['label' => 'Page1', 'content' => 'Page2'],
                        ['label' => 'Page2', 'content' => 'Page3'],
                    ],
                ],
            ])
            ->render();
        $expected = <<<'HTML'
        <ul id="w0-dropdown" class="dropdown-menu" aria-expanded="false">
        <li class="dropdown" aria-expanded="false"><a class="dropdown-item dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false" role="button">&lt;span&gt;&lt;i class=fas fastest&gt;&lt;/i&gt;Dropdown1&lt;/span&gt;</a><ul id="w1-dropdown" class="dropdown-menu" aria-expanded="false">
        <li><h6 class="dropdown-header">Page1</h6></li>
        <li><h6 class="dropdown-header">Page2</h6></li>
        </ul></li>
        </ul>
        HTML;
        $this->assertEqualsWithoutLE($expected, $html);

        $html = Dropdown::widget()
            ->withoutEncodeLabels()
            ->items([
                [
                    'label' => '<span><i class=fas fastest></i>Dropdown1</span>',
                    'items' => [
                        ['label' => 'Page1', 'content' => 'Page2'],
                        ['label' => 'Page2', 'content' => 'Page3'],
                    ],
                ],
            ])
            ->render();
        $expected = <<<'HTML'
        <ul id="w2-dropdown" class="dropdown-menu" aria-expanded="false">
        <li class="dropdown" aria-expanded="false"><a class="dropdown-item dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false" role="button"><span><i class=fas fastest></i>Dropdown1</span></a><ul id="w3-dropdown" class="dropdown-menu" aria-expanded="false">
        <li><h6 class="dropdown-header">Page1</h6></li>
        <li><h6 class="dropdown-header">Page2</h6></li>
        </ul></li>
        </ul>
        HTML;
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testMainOptions(): void
    {
        Dropdown::counter(0);

        $html = Dropdown::widget()
            ->withoutEncodeLabels()
            ->itemOptions([
                'class' => 'main-item-class',
            ])
            ->linkOptions([
                'class' => 'main-link-class',
            ])
            ->items([
                [
                    'label' => 'Label 1',
                    'url' => '#',
                ],
                [
                    'label' => 'Label 2',
                    'url' => '#',
                    'options' => [
                        'id' => 'custom-item-id',
                        'class' => 'custom-item-class',
                    ],
                    'linkOptions' => [
                        'class' => 'custom-link-class',
                    ],
                ],
            ])
            ->render();

        $expected = <<<'HTML'
        <ul id="w0-dropdown" class="dropdown-menu" aria-expanded="false">
        <li class="main-item-class"><a class="main-link-class dropdown-item" href="#">Label 1</a></li>
        <li id="custom-item-id" class="custom-item-class"><a class="custom-link-class dropdown-item" href="#">Label 2</a></li>
        </ul>
        HTML;

        $this->assertEqualsWithoutLE($expected, $html);
    }
}
