<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information please see
 * <http://phing.info>.
*/

include_once 'class.JavaScriptPacker.php';
include_once 'phing/filters/BaseParamFilterReader.php';
include_once 'phing/filters/ChainableReader.php';

/**
 * This filter uses the PHP port of Dean Edwards Packer, JavaScriptPacker.  This file was modeled after the XsltFilter and TidyFilter included with Phing.
 * 
 * <p>
 * Sample build.xml target:<br/>
 * <pre>
 * 	<target name="pack-javascript">
 *		<copy todir="./Web/js" overwrite="true">
 *			<fileset dir="../Web/js">
 *				<include name="*.js" />
 *			</fileset>
 *
 *			<filterchain>
 *				<filterreader classname="phing.filters.JSPackerFilter">
 *				  <param name="encoding" value="62"/><!-- 0,10,62,95 or 'None', 'Numeric', 'Normal', 'High ASCII'. -->
 *				  <param name="fastDecode" value="true"/><!-- include the fast decoder in the packed result -->
 *				  <param name="specialCharacters" value="false"/><!-- have you flagged your private and local variables in the script -->
 *				</filterreader>
 *			</filterchain>
 *		</copy>
 * 	</target>
 * </pre>
 * 
 * @author Zach Leatherman <http://www.zachleat.com>
 * @version   $Revision: 1.0 $ $Date: 2007/08/10 23:29 $
 * @package   phing.filters
 */
class JSPackerFilter extends BaseParamFilterReader implements ChainableReader {

	private $encoding;
	private $fastDecode;
	private $specialCharacters;

	/**
	 * Set the encoding for packing
	 * @param string or int $v
	 */
	public function setEncoding($v) {
		$this->encoding = $v;
	}

	/**
	 * Set whether include the fast decoder in the packed result
	 * @param string $v
	 */
	public function setFastDecode($v) {
		$this->fastDecode = $v;
	}

	/**
	 * Set if you flagged your private and local variables in the script
	 * @param string $v
	 */
	public function setSpecialCharacters($v) {
		$this->specialCharacters = $v;
	}

	/**
	 * Get the encoding for packing
	 * @return String
	 */
	public function getEncoding() {
		return $this->encoding;
	}

	/**
	 * Get whether include the fast decoder in the packed result
	 * @return String
	 */
	public function getFastDecode() {
		return $this->fastDecode;
	}

	/**
	 * Get if you flagged your private and local variables in the script
	 * @return String
	 */
	public function getSpecialCharacters() {
		return $this->specialCharacters;
	}
	
	/**
	 * Adds a <config> element (which is a Parameter).
	 * @return Parameter
	 */
	public function createConfig() {
		$num = array_push($this->configParameters, new Parameter());
        return $this->configParameters[$num-1];
	}
	
    /**
     * Reads input and returns JSPacker-filtered output.
     * 
     * @return the resulting stream, or -1 if the end of the resulting stream has been reached
     * 
     * @throws IOException if the underlying stream throws an IOException
     *                        during reading     
     */
    function read($len = null) {
    	
		if (!class_exists('JavaScriptPacker')) {
			throw new BuildException("You must include the JavaScriptPacker class before you can use the JSPackerFilter.");
		}
		if ( !$this->getInitialized() ) {
            $this->_initialize();
            $this->setInitialized(true);
        }

		$_js = null;
        while ( ($data = $this->in->read($len)) !== -1 )
            $_js .= $data;

        if ($_js === null ) { // EOF?
            return -1;
        }

		$out = '';
		try {
            $myPacker = new JavaScriptPacker($_js, $this->getEncoding(), $this->getFastDecode(), $this->getSpecialCharacters);
			$out = $myPacker->pack();
        } catch (Exception $e) {            
            throw new BuildException($e);
        }

		return $out;
    }


    /**
     * Creates a new JSPackerFilter using the passed in Reader for instantiation.
     * 
     * @param reader A Reader object providing the underlying stream.
     *               Must not be <code>null</code>.
     * 
     * @return a new filter based on this configuration, but filtering
     *         the specified reader
     */
    public function chain(Reader $reader) {
        $newFilter = new JSPackerFilter($reader);
		$newFilter->setInitialized(true);
		$newFilter->setEncoding($this->getEncoding());
		$newFilter->setFastDecode($this->getFastDecode());
		$newFilter->setSpecialCharacters($this->getSpecialCharacters());
        return $newFilter;
    }
	
	/**
     * Initializes any parameters (e.g. config options).
     * This method is only called when this filter is used through a <filterreader> tag in build file.
     */
    private function _initialize() {        
        $params = $this->getParameters();
        if ( $params !== null ) {
            for($i = 0, $_i=count($params) ; $i < $_i; $i++) {
                if ( $params[$i]->getType() === null ) {
                    if ($params[$i]->getName() === "encoding") {
                        $this->setEncoding($params[$i]->getValue());
                    } elseif ($params[$i]->getName() === "fastDecode") {
						$this->setFastDecode($params[$i]->getValue());
					} elseif ($params[$i]->getName() === "specialCharacters") {
						$this->setSpecialCharacters($params[$i]->getValue());
					}
                }
            }
        }
    }
}
