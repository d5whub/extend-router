<?php

/**
 * This file is part of d5whub extend router
 * @author Vitor Reis <vitor@d5w.com.br>
 * @noinspection PhpUnhandledExceptionInspection
 */

namespace D5WHUB\Test\Extend\Router;

use D5WHUB\Extend\Router\Context;
use D5WHUB\Extend\Router\Context\Header\ContextState;
use D5WHUB\Extend\Router\Exception\RuntimeException;
use D5WHUB\Extend\Router\Exception\SyntaxException;
use D5WHUB\Extend\Router\Router;
use D5WHUB\Test\Extend\Router\UnitTest\MiddlewareByClassMethodWithConstructContext;
use D5WHUB\Test\Extend\Router\UnitTest\MiddlewareByClassMethodWithParams;
use D5WHUB\Test\Extend\Router\UnitTest\MiddlewareByClassStaticMethod;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    public function testSimple()
    {
        $router = new Router();
        $router
            ->get('/', static function () {
                return 'TEST:GET';
            })
            ->post('/', static function () {
                return 'TEST:POST';
            })
            ->put('/', static function () {
                return 'TEST:PUT';
            })
            ->patch('/', static function () {
                return 'TEST:PATCH';
            })
            ->delete('/', static function () {
                return 'TEST:DELETE';
            })
            ->options('/', static function () {
                return 'TEST:OPTIONS';
            })
            ->head('/', static function () {
                return 'TEST:HEAD';
            });

        # GET
        $match = $router->match('GET', '/');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST:GET', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        # POST
        $match = $router->match('POST', '/');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST:POST', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        # PUT
        $match = $router->match('PUT', '/');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST:PUT', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        # PATCH
        $match = $router->match('PATCH', '/');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST:PATCH', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        # DELETE
        $match = $router->match('DELETE', '/');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST:DELETE', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        # OPTIONS
        $match = $router->match('OPTIONS', '/');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST:OPTIONS', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        # HEAD
        $match = $router->match('HEAD', '/');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST:HEAD', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testSimpleAny()
    {
        $router = new Router();
        $router->any('/', static function () {
            return 'TEST:ANY';
        });

        # GET
        $match = $router->match('GET', '/');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST:ANY', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        # POST
        $match = $router->match('POST', '/');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST:ANY', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        # PUT
        $match = $router->match('PUT', '/');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST:ANY', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        # PATCH
        $match = $router->match('PATCH', '/');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST:ANY', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        # DELETE
        $match = $router->match('DELETE', '/');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST:ANY', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        # OPTION
        $match = $router->match('OPTIONS', '/');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST:ANY', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        # HEAD
        $match = $router->match('HEAD', '/');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST:ANY', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testWithParams()
    {
        $router = new Router();
        $router
            ->get('/:var1/xxx/:var2', static function ($var1, $var2) {
                return "$var1:$var2";
            })
            ->get('/:var1', static function (Context $context) {
                return (string)$context->current->params->var1;
            });

        $match = $router->match('GET', '/AAA/xxx/111');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('AAA:111', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        $match = $router->match('GET', '/ABC123');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('ABC123', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testWithOmittedParamsOnMiddleware()
    {
        $router = new Router();
        $router->get('/:var1/:var2/:var3', static function ($var1, $var3) {
            return "$var1:$var3";
        });

        $match = $router->match('GET', '/AAA/BBB/CCC');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $context = $match->execute();
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        $this->assertEquals((object)['var1' => 'AAA', 'var2' => 'BBB', 'var3' => 'CCC'], $context->current->params);
        $this->assertEquals('AAA:CCC', $context->result);
    }

    public function testWithFilter()
    {
        $router = new Router();
        $router
            ->get('/:id[D]', static function ($id) {
                return "TEST:IS_LETTER";
            })
            ->get('/:id[d]', static function ($id) {
                return "TEST:IS_NUMBER";
            });

        # \D+
        $match = $router->match('GET', '/aaa');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST:IS_LETTER', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        # \d+
        $match = $router->match('GET', '/111');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST:IS_NUMBER', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testJoker()
    {
        $router = new Router();
        $router->get('/user/*', static function () {
            return "TEST";
        });

        $match = $router->match('GET', '/user/aaa');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        $match = $router->match('GET', '/user/bbb');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testWithMultiMiddlewares()
    {
        $results = [];
        $router = new Router();
        $router
            ->get('/user/:uid', static function ($uid) use (&$results) {
                $results[] = "m1:u$uid";
            }, static function ($uid) use (&$results) {
                $results[] = "m2:u$uid";
            })
            ->any('/user/:uid', static function ($uid) use (&$results) {
                $results[] = "m3:u$uid";
            })
            ->get('/user/:user_id', static function ($user_id) use (&$results) {
                $results[] = "m4:u$user_id";
            });

        $match = $router->match('GET', '/user/1');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertNull($match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        $this->assertEquals(['m1:u1', 'm2:u1', 'm3:u1', 'm4:u1'], $results);
    }

    public function testContext()
    {
        $router = new Router();
        $router
            ->get(
                '/:user_num',
                static function ($context) {
                    return ["m1:u{$context->current->params->user_num}"];
                },
                static function ($user_num, $context) {
                    return array_merge($context->result, ["m2:u$user_num"]);
                }
            )
            ->get(
                '/:user_id',
                static function ($context) {
                    return array_merge($context->result, ["m3:u{$context->current->params->user_id}"]);
                }
            )
            ->any('/:uid', static function ($uid, Context $context) {
                return array_merge($context->result, ["m4:u$uid"]);
            });

        $match = $router->match('GET', '/333');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals(["m1:u333", "m2:u333", "m3:u333", "m4:u333"], $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testWithContextSetData()
    {
        $router = new Router();
        $router->get('/', static function (Context $context) {
            return "EXTRA:{$context->get('zzz')}";
        });

        $match = $router->match('GET', '/');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals("EXTRA:111", $match->set('zzz', 111)->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testPersistingData()
    {
        $router = new Router();
        $router
            ->get('/:var1/:var2', static function ($var1, $var2, Context $context) {
                $context->set('xxx', $context->get('xxx') + $var1 + $var2);
                return $context->get('xxx');
            })
            ->get('/:var1/:var2', static function ($var1, $var2, Context $context) {
                $context->set('xxx', $context->get('xxx') + $var1 + $var2);
                return $context->result;
            });

        $match = $router->match('GET', '/111/222');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals(666, $match->set('xxx', 333)->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
        $this->assertEquals(999, $match->get('xxx'));
    }

    public function testFriendly()
    {
        $router = new Router();
        $router
            ->get('/user/:user_id', static function ($user_id, Context $context) {
                return "u:$user_id";
            })
            ->friendly('/vitor', '/user/111');

        $match = $router->match('GET', '/vitor');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals("u:111", $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testCustomFilter()
    {
        $router = new Router();
        $router
            ->addFilter('only_number', '\d+')
            ->get('/:var1[only_number]', static function () {
                return 'CUSTOM_FILTER';
            });

        # OK
        $match = $router->match('GET', '/100');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('CUSTOM_FILTER', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        # ERROR
        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage("Route \"/aaa\" not found!");
        $router->match('GET', '/aaa');
    }

    public function testNotFound()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage("Route \"/bbb\" not found!");

        (new Router())
            ->get('/aaa', static function () {
                return 'OK';
            })
            ->match('POST', '/bbb');
    }

    public function testMethodNotAllowed()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(405);
        $this->expectExceptionMessage("Method \"POST\" not allowed for route \"/aaa\"!");

        (new Router())
            ->get('/aaa', static function () {
                return 'OK';
            })
            ->match('POST', '/aaa');
    }

    public function testSyntaxErrorInvalidHttpMethod()
    {
        $this->expectException(SyntaxException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage("Http method \"XXX\" invalid");

        (new Router())->addRoute('XXX', '/', static function () {
            return '';
        });
    }

    public function testSyntaxErrorParamDuplicateName()
    {
        $this->expectException(SyntaxException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage("Param with duplicate name \":var1\"");

        (new Router())->get('/:var1/:var1', static function () {
            return '';
        });
    }

    public function testSyntaxErrorNotImplementedFilter()
    {
        $this->expectException(SyntaxException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage("Filter \"xxx\" not implemented");

        (new Router())->get('/:var1[xxx]', static function () {
            return '';
        });
    }

    public function testRequiredArgumentError()
    {
        function requiredArgumentError($var1)
        {
            return "var1:$var1";
        }

        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage(
            "Required argument \"var1\" for invoke \"D5WHUB\\Test\\Extend\\Router\\requiredArgumentError\"!"
        );

        (new Router())
            ->get('/:var2', '\\D5WHUB\\Test\\Extend\\Router\\requiredArgumentError')
            ->match('GET', '/100')
            ->execute();
    }

    public function testRuntimeInvalidHttpMethod()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage("Http method \"XXX\" invalid");

        (new Router())->match('XXX', '/100');
    }

    public function testRuntimeErrorClassNotFound()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(500);
        if (version_compare(PHP_VERSION, '8', '<')) {
            $this->expectExceptionMessage("Class \\~ does not exist");
        } else {
            $this->expectExceptionMessage("Class \"\\~\" does not exist");
        }

        (new Router())
            ->get('/:var2', '\\~::~notFoundMethod')
            ->match('GET', '/100')
            ->execute();
    }

    public function testRuntimeErrorClassMethodNotFound()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage("Method " . self::class . "::~notFoundMethod() does not exist");

        (new Router())
            ->get('/:var2', self::class . '::~notFoundMethod')
            ->match('GET', '/100')
            ->execute();
    }

    public function testRuntimeErrorFunctionNotFound()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage("Function ~notFoundFunction() does not exist");

        (new Router())
            ->get('/:var2', '~notFoundFunction')
            ->match('GET', '/100')
            ->execute();
    }

    public function testWithMiddlewareByFunctionName()
    {
        function middlewareNamedFunction(Context $context)
        {
            return "uid:{$context->current->params->uid}";
        }

        $router = new Router();
        $router->get('/:uid', '\\D5WHUB\\Test\\Extend\\Router\\middlewareNamedFunction');

        $match = $router->match('GET', '/111');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('uid:111', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testWithMiddlewareByNativeFunction()
    {
        $router = new Router();
        $router->get('/:haystack/:needle', 'stripos');

        $match = $router->match('GET', '/PHChat/Chat');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals(2, $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testWithMiddlewareByAnonymousFunction()
    {
        $router = new Router();
        $router->get('/:var1/:var2', static function ($var1, $var2) {
            return $var1 + $var2;
        });

        $match = $router->match('GET', '/111/222');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals(333, $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testWithMiddlewareByArrowFunction()
    {
        if (version_compare(PHP_VERSION, '7.4', '<')) {
            $this->markTestSkipped('PHP 7.4+ required');
        } else {
            $router = new Router();
            $router->get('/:var1/:var2', eval('return static fn($var1, $var2) => $var1 + $var2;'));

            $match = $router->match('GET', '/111/222');
            $this->assertInstanceOf(Context::class, $match);
            $this->assertEquals(ContextState::PENDING, $match->header->state);
            $this->assertEquals(333, $match->execute()->result);
            $this->assertEquals(ContextState::COMPLETED, $match->header->state);
        }
    }

    public function testWithMiddlewareByVariableFunction()
    {
        $func = static function ($var1, $var2) {
            return $var1 + $var2;
        };

        $router = new Router();
        $router->get('/:var1/:var2', $func);

        $match = $router->match('GET', '/111/222');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals(333, $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testWithMiddlewareByClassStaticMethodArray()
    {
        $router = new Router();
        $router->get('/:var1/:var2', [MiddlewareByClassStaticMethod::class, 'params']);

        $match = $router->match('GET', '/111/222');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals(333, $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testWithMiddlewareByClassStaticMethodString()
    {
        $router = new Router();
        $router->get(
            '/:var1/:var2',
            "\\D5WHUB\\Test\\Extend\\Router\\UnitTest\\MiddlewareByClassStaticMethod::context"
        );

        $match = $router->match('GET', '/111/222');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals(333, $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testWithMiddlewareByClassStaticMethodObject()
    {
        $class = new MiddlewareByClassStaticMethod();

        $router = new Router();
        $router->get('/:var1/:var2', [$class, "context"]);

        $match = $router->match('GET', '/111/222');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals(333, $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testWithMiddlewareByClassMethodWithParamsArray()
    {
        $router = new Router();
        $router->get('/:var1/:var2', [MiddlewareByClassMethodWithParams::class, 'execute']);

        $match = $router->match('GET', '/111/222');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals(333, $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testWithMiddlewareByClassMethodWithParamsString()
    {
        $router = new Router();
        $router->get(
            '/:var1/:var2',
            "\\D5WHUB\\Test\\Extend\\Router\\UnitTest\\MiddlewareByClassMethodWithParams::execute"
        );

        $match = $router->match('GET', '/111/222');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals(333, $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testWithMiddlewareByClassMethodWithParamsObject()
    {
        $class = new MiddlewareByClassMethodWithParams();

        $router = new Router();
        $router->get('/:var1/:var2', [$class, "execute"]);

        $match = $router->match('GET', '/111/222');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals(333, $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testWithMiddlewareByClassMethodWithConstructContextArray()
    {
        $router = new Router();
        $router->get('/:var1/:var2', [MiddlewareByClassMethodWithConstructContext::class, 'execute']);

        $match = $router->match('GET', '/111/222');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals(333, $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testWithMiddlewareByClassMethodWithConstructContextString()
    {
        $router = new Router();
        $router->get(
            '/:var1/:var2',
            "\\D5WHUB\\Test\\Extend\\Router\\UnitTest\\MiddlewareByClassMethodWithConstructContext::execute"
        );

        $match = $router->match('GET', '/111/222');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals(333, $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testWithMiddlewareByAnonymousClassInvokeMethod()
    {
        if (version_compare(PHP_VERSION, '7', '<')) {
            $this->markTestSkipped('PHP 7+ required');
        } else {
            $router = new Router();
            $router->get(
                '/:var1/:var2',
                eval('return new class {
                    public function __invoke(\D5WHUB\Extend\Router\Context $context)
                    {
                        return $context->current->params->var1 + $context->current->params->var2;
                    }
                };')
            );

            $match = $router->match('GET', '/111/222');
            $this->assertInstanceOf(Context::class, $match);
            $this->assertEquals(ContextState::PENDING, $match->header->state);
            $this->assertEquals(333, $match->execute()->result);
            $this->assertEquals(ContextState::COMPLETED, $match->header->state);
        }
    }

    public function testWithMiddlewareByAnonymousClassMethodWithConstructContextArray()
    {
        if (version_compare(PHP_VERSION, '7', '<')) {
            $this->markTestSkipped('PHP 7+ required');
        } else {
            $router = new Router();
            $router->get('/:var1/:var2', [
                eval('return new class {
                    public function execute($var1, $var2)
                    {
                        return $var1 + $var2;
                    }
                };'),
                'execute'
            ]);
        }

        $match = $router->match('GET', '/111/222');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals(333, $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }

    public function testStopPropagation()
    {
        $router = new Router();
        $router
            ->get('/aaa', static function ($context) {
                $steps = $context->get('steps', []);
                $steps[] = 1;
                $context->set('steps', $steps);
                return 1;
            })
            ->any('/aaa', static function (Context $context) {
                $steps = $context->get('steps', []);
                $steps[] = 2;
                $context->set('steps', $steps);
                $context->stop();
                return 2;
            })
            ->get('/:var', static function ($context) {
                $steps = $context->get('steps', []);
                $steps[] = 3;
                $context->set('steps', $steps);
                return 3;
            });

        $match = $router->match('GET', '/aaa');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals(2, $match->execute()->result);
        $this->assertEquals(ContextState::STOPPED, $match->header->state);
        $this->assertEquals([1, 2], $match->get('steps'));
    }

    public function testLooseFilter()
    {
        $router = new Router();
        $router
            ->addFilter('cf', '(\d{2})')
            ->get('/user/[az]/[cf]/:var[cf]', static function () {
                return "TEST";
            });

        $match = $router->match('GET', '/user/aaa/12/12');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals('TEST', $match->execute()->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage("Route \"/user/AAA/1/12\" not found!");
        $router->match('GET', '/user/AAA/1/12');
    }

    public function testSyntaxErrorContextReservedName()
    {
        $this->expectException(SyntaxException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage("Param with reserved name \":context\"");

        (new Router())->get('/:context', static function () {
            return '';
        });
    }

    public function testWithExecuteCallback()
    {
        $router = new Router();
        $router->get('/:var1/:var2', static function ($var1, $var2) {
            return [$var1, $var2];
        });

        $match = $router->match('GET', '/111/222');
        $this->assertInstanceOf(Context::class, $match);
        $this->assertEquals(ContextState::PENDING, $match->header->state);
        $this->assertEquals(666, $match->execute(function ($context) {
            $context->result = $context->result[0] + $context->result[1] + 333;
        })->result);
        $this->assertEquals(ContextState::COMPLETED, $match->header->state);
    }
}
